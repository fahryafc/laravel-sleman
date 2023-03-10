@extends('layouts.admin')

@section('content')

    <div class="card">
        <div class="card-header">{{ __('View User') }}</div>

        <div class="card-body">

            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Back to List</a>

            <br /><br />



            <table class="table table-borderless table-hover">
                        <tr class="bg-info text-light">
                            <th class="text-center">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Role</th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        @forelse ($m_user as $user)
                        <tr>
                            <td class="text-center">{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td><img src="/images/{{ $user->image }}" width="100px"></td>
                            <td>{{$user->title}}</td>
                            <td>
                                    @can('user_show')
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-success">Show</a>
                                    @endcan
                                    @can('user_edit')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('user_delete')
                                <form action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline-block" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                                </form>
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

@endsection
