@extends('layouts.admin')

@section('content')

    <div class="card">
        <div class="card-header">{{ __('Users List') }}</div>

        <div class="card-body">
            @can('user_create')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
            @endcan

            <br/><br/>
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless table-hover">
                                <tr class="bg-info text-light">
                                    <th class="text-center">ID</th>
                                    <th>Role Name</th>
                                    <th>Total User</th>
                                    <th>Role ID</th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                        @forelse ($m_user as $user)
                            <tr>
                                <td class="text-center">{{$user->id}}</td>
                                <td>{{$user->title}}</td>
                                <td>{{$user->total}}</td>
                                <td>
                                    @can('user_show')
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-success">Show</a>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted py-3">No Users Found</td>
                                </tr>
                        @endforelse
                    </table>
                </div>
            </div>




            @if($users->total() > $users->perPage())
            <br><br>
            {{$users->links()}}
            @endif

        </div>
    </div>

@endsection
