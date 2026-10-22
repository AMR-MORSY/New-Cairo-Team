<?php

namespace App\Livewire\Sites\Actions;

use Toaster;
use App\Models\User;
use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\MuxPlan;
use App\Models\Site\SheetImport;
use App\Contracts\SiteInterface;
use App\Livewire\Forms\SiteForm;
use Livewire\Attributes\Title;
use App\Repositories\SiteRepository;
use App\Services\Sites\SiteRouteMap;


#[Title('Show Site')]
class ShowSite extends Component
{

    public SiteForm $form;
    // protected $siteRepository;

    public Site $site;

    /** The chain built from the cascades table: one parent per site. */
    // public $route;

    /**
     * Routes as the planning sheet draws them. A site can sit on more than
     * one, which the cascades table cannot express, so these are kept apart
     * rather than merged into $route.
     *
     * @var array<int, array<int, array{code: string, name: ?string, mmu_near: ?string, mmu_far: ?string}>>
     */
    public array $sheetRoutes = [];

    /** The sheet draws this site at the head of a chain. */
    public bool $isSheetHub = false;

    /**
     * The site and everything cascading off it, laid out as the sheet draws
     * it: merged blocks and the planners' colours.
     */
    public array $sheetGrid = [];

    /**
     * One sheet grid per route that reaches this site, so two ways in read
     * as two drawings rather than one merged block.
     *
     * @var array<int, array{route: array<int, string>, grid: array}>
     */
    public array $sheetRouteGrids = [];

    /** Distinct sites below this one, which the grid can repeat. */
    public int $sheetCascadeCount = 0;

    /** When the sheet behind that grid was last read, for an honest "as of". */
    public ?string $sheetImportedAt = null;

    public ?string $sheetSource = null;

    // public ?array $newDirectCascades = null;

    public ?array $muxPlans = null;

    // public ?array $indirectCascades = null;

    // public  $previousUrl;

    public string $page = "site.show";

    public function mount(SiteRepository $siteRepository, Site $site)
    {
        // the only check this page had was ->can('view', 'site') on the route;
        // it lives here now so the component is not open when mounted directly
        $this->authorize('view', $site);

        // $this->previousUrl = url()->previous();

        // $this->siteRepository = $siteRepository;

        $this->form->setSite($site); ////this function binds the $site model with the form which contains the site model attributes which could be accessed inside the view 'livewire.sites.actions.show-site' by using the object form

        // form->zone / form->team already hold the enum codes

        $this->site = $site;

        // $this->route = $this->getSiteRoute();

        $map = new SiteRouteMap();
        $this->sheetRoutes = $map->routesTo($site->site_code);
        $this->isSheetHub = $map->isHub($site->site_code);
        $this->sheetGrid = $map->sheetGrid($site->site_code);
        $this->sheetRouteGrids = $map->sheetGridsByRoute($site->site_code);
        $this->sheetCascadeCount = count($map->cascadedCodes($site->site_code));

        $import = SheetImport::query()->orderByDesc('imported_at')->first();
        $this->sheetImportedAt = $import?->imported_at?->diffForHumans();
        $this->sheetSource = $import?->source;




        // $this->indirectCascades = $this->siteRepository->indirectCascades($site->cascades);
        // $this->indirectCascades = collect($this->indirectCascades);

    }

    // private function getSiteRoute()
    // {
    //     $nodalCode = $this->site->getNodalNameAndCode('code');
    //     $nodals = [];

    //     if ($nodalCode) {
    //         $nodals = $this->siteRepository->nodals($nodalCode);
    //         $stringNodals = implode('', $nodals);
    //         $route = "{$this->site->site_code}_{$this->site->site_name}{$stringNodals}";

    //         return $route;
    //     }
    //     return null;
    // }
    // public function goBack()
    // {

    //     return redirect()->to($this->previousUrl);
    // }

    public function muxPlan()
    {
        $this->authorize('viewSiteProperties', $this->site);
        $site_code = $this->site->site_code;
        $muxPlans = MuxPlan::where('ne', 'like', "%$site_code%")->get();
        if (count($muxPlans) > 0) {

            $this->muxPlans = $muxPlans;
            $this->dispatch('mux_plans', mux_plans: $muxPlans);
        } else {
            $muxPlans = MuxPlan::where('fe', 'like', "%$site_code%")->get();
            if (count($muxPlans) > 0) {
                $this->muxPlans = $muxPlans;
                $this->dispatch('mux_plans', mux_plans: $muxPlans);
            } else {
                Toaster::error('No Mux Plans available');
            }
        }
    }

    public function render()
    {
        return view('livewire.sites.actions.show-site');
    }
}
