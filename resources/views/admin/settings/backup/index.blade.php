@extends('layouts.dashboard.index')

@section('content')

 <div class="card-body">
            <h2 class="card-title">Manual Backup</h2>
            <form method="POST" action="{{ route('manual.backup') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Perform System Backup</button>
            </form>

        </div>
@endsection