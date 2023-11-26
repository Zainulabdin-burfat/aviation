@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Transactions</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user_id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection