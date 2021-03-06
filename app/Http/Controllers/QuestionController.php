<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\Question;
use App\Http\Requests\QuestionRequest;
use Auth;
use DB;

class QuestionController extends Controller
{
    public function __construct() 
    {
            view()->composer('back.partials.nav', function($view){
        
            $categories = DB::table('categories')->select('name', 'id')->get();

            $view->with('categories', $categories);
        });

            view()->composer('back.partials.sidebar', function($view){

            $user = Auth::user();

            $view->with('user', $user);
        });

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all()->latest();

        return view('back.dashboard', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::pluck('name', 'id'); //Récupère le titre et l'id en renvoyant un tableau Array_collection
        
        return view('back.question.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {  
        $question = Question::create( $request->all() );

        $question->status = 'unpublished';

        if($request->status == 'on') $question->status = 'published';

        $question->save();

        session()->flash('flashMessage', 'La question a été ajoutée');

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Récupération des datas sur la question
        $question = Question::find($id);

        //Récupération des datas nécéssaires au formulaire
        $category = Category::pluck('name', 'id');

        return view('back.question.edit', compact('question', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, $id)
    {   
        //Récupération des informations de la question
        $question = Question::find($id);

        $question->update( $request->all() );

        $question->status = 'unpublished';

        if($request->status == 'on') $question->status = 'published';

        $question->save();

        session()->flash('flashMessage', 'Modification effectuée');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Question::destroy($id);

        //Flash message de confirmation
        session()->flash('flashMessage', 'Suppression effectuée');

        //Redirection vers le dashboard
        return redirect()->route('dashboard');
    }
}