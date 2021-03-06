@extends('layouts.admin')

@section('content')
<div class="row center">
    {{$questions->links()}}
</div>
<div class="row">
    <div class="col s12">
    <a href="{{route('question.create')}}" class="btn blue-grey darken-1">Ajouter une nouvelle question</a>
    </div>
</div>
@if ( $flash = session('flashMessage') )
  <div class="chip {{($flash == 'Suppression effectuée') ? 'red' : 'green'}} lighten-3" id="flash">
    <div class="col s12" id="flash">{{$flash}}</div>
  </div>
@endif
    <table class="striped">
       <thead>
         <tr>
             <th>Titre</th>
             <th>Publiée</th>
             <th>Catégorie</th>
             <th>Date</th>
             <th>Action</th>
         </tr>
       </thead>
    <tbody>
    @foreach ($questions as $question)
    <tr>
        <td><a href="{{route('question.edit', $question->id)}}">{{$question->title}}</a></td>
        <td><i class="material-icons">{{($question->status == 'published') ? 'done' : 'loop'}}</i></td>
        <td><a href="{{($question->category) ? route('cat', $question->category->id) : '#' }}"<div class="btn 
{{($question->category) ? ($question->category->id == 1 ? 'blue' : 'green') : 'grey'}}">{{($question->category) ? $question->category->name : 'Sans catégorie'}}</div></td>
        <td>{{$question->date}}</td>
        <td>
            <a href="{{route('question.edit', $question->id)}}"><button class="waves-effect waves-blue btn blue lighten-3"><i class="material-icons">mode_edit</i></button></a>

            <form action="{{route('question.destroy', $question->id)}}" method="post">
                <!-- insertion du token -->
                <div class="row">
                  {{csrf_field()}}
                  <!-- Utilisation de la méthode DELETE -->
                  {{ method_field('DELETE') }}
                </div>
                <button type="submit" name="action" class="waves-effect waves-orange btn red lighten-3"><i class="material-icons">delete</i></button>
            </form>
        </td>
    </tr>
    
    @endforeach
    </tbody>
</table>

<div class="row center">
    {{$questions->links()}}
</div>

@endsection