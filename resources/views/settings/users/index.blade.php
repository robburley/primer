@extends('layouts.master')

@section('page-title')
    Users
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Users</h3>

        <a class="c-btn c-btn--blue u-ml-auto"
           href="{{ route('settings.users.create') }}">
            New User
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table c-table--zebra u-mb-small">
                        <thead class="c-table__head">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head">Name</th>
                                <th class="c-table__cell c-table__cell--head">Username</th>
                                <th class="c-table__cell c-table__cell--head">Role</th>
                                <th class="c-table__cell c-table__cell--head">Created</th>
                                <th class="c-table__cell c-table__cell--head"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $user)
                                <tr class="c-table__row @if(!$user->active) c-table__row--danger @endif">
                                    <td class="c-table__cell">
                                        {{ $user->full_name }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $user->username }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $user->role_names ?? 'No Role' }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>

                                    <td class="c-table__cell">
                                        <a class="c-btn c-btn--blue pull-right"
                                           href="{{ route('settings.users.edit', $user) }}">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="c-table__row">
                                    <td class="c-table__cell" colspan="4">There are no users</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $users->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
    </div>
@endsection
