<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Articles;
use Illuminate\Support\Facades\DB;
use App\Settings;

class SearchComponent extends Component
{
    public $search;
    public $bts;

    protected $queryString = ['search'];

    public function mount()
    {
        $bts = Settings::first();
        $this->bts = $bts->bts;
        
    }

    public function render()
    {   
    
        if(strlen($this->search))
        {

            $search1 = Articles::where('published', 1)
            ->where('approved', 1)
            ->when($this->bts, function ($q){
                $q->where('bts', 1);
            })
            ->where(function ($q) {
            $q->where('title','like','%' . $this->search . '%')
            ->orWhere('tags','like','%' . $this->search . '%');
            })
            ->get();

         
            foreach($search1 as $record)
            {
                $row[] = $record->id;
            }



            if($this->bts)
            {
                if (isset($row))
                {
                $sql = "SELECT articles.id,articles.title,articles.views, articles.author_name as author_name, articles.kb as kb, articles.created_at as created_at, articles.rating as rating
                                FROM articles
                                join article_bodies ON articles.id = article_bodies.id
                                WHERE MATCH(article_bodies.body) AGAINST(? IN boolean mode) AND articles.published = 1 AND articles.approved = 1 AND articles.bts = 1 AND article_bodies.id NOT IN (". implode(',', $row) .")";
                } else
                {
                    $sql = "SELECT articles.id,articles.title,articles.views, articles.author_name as author_name, articles.kb as kb, articles.created_at as created_at, articles.rating as rating
                                FROM articles
                                join article_bodies ON articles.id = article_bodies.id
                                WHERE MATCH(article_bodies.body) AGAINST(? IN boolean mode) AND articles.published = 1 AND articles.approved = 1 AND articles.bts = 1";

                }

            }
            else
            {
                if (isset($row))
                {
                $sql = "SELECT articles.id,articles.title,articles.views, articles.author_name as author_name, articles.kb as kb, articles.created_at as created_at, articles.rating as rating
                                FROM articles
                                join article_bodies ON articles.id = article_bodies.id
                                WHERE MATCH(article_bodies.body) AGAINST(? IN boolean mode) AND articles.published = 1 AND articles.approved = 1 AND article_bodies.id NOT IN (". implode(',', $row) .")";
                } else
                {
                    $sql = "SELECT articles.id,articles.title,articles.views, articles.author_name as author_name, articles.kb as kb, articles.created_at as created_at, articles.rating as rating
                                FROM articles
                                join article_bodies ON articles.id = article_bodies.id
                                WHERE MATCH(article_bodies.body) AGAINST(? IN boolean mode) AND articles.published = 1 AND articles.approved = 1";

                }

            }



            $body = DB::select($sql,[$this->search]);
            $allRows = collect($search1)->merge($body)->unique('id');
            $articles = $allRows;
        }
        else
        {
            $articles = [];
        }

        return view('livewire.search-component',['articles' => $articles]);
    
    }

    public function deleteArticle(Articles $article)
    {
        $article->delete();
        $this->dispatchBrowserEvent('update-success');
    }
}
