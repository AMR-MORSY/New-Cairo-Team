<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use App\Livewire\Forms\PostForm;

class CreatePost extends Component
{
     public PostForm $form; 
      public function save()
    {
        $this->validate();
 
        Post::create(
            $this->form->all() 
        );
 
        return $this->redirect('/posts');
    }
    public function render()
    {
        return view('livewire.create-post');
    }
}
