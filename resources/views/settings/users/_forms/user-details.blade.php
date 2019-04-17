<div class="row">
    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="first_name">First Name</label>

        {!! Form::text('first_name', null, ['class' => 'c-input', 'placeholder' => 'Jason', 'id' => 'first_name']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="last_name">Last Name</label>

        {!! Form::text('last_name', null, ['class' => 'c-input', 'placeholder' => 'Clark', 'id' => 'last_name']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="username">Username</label>

        {!! Form::text('username', null, ['class' => 'c-input', 'placeholder' => 'jasonclark', 'id' => 'username']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="email">E-mail Address</label>

        {!! Form::email('email', null, ['class' => 'c-input', 'placeholder' => 'user@example.org']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="password">Password</label>

        {!! Form::password('password', ['class' => 'c-input']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="password_confirmed">Password
            Confirmed</label>

        {!! Form::password('password_confirmation', ['class' => 'c-input']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="role_id">Role</label>

        {!! Form::select('role_id', FormPopulator::roles() , isset($user) && $user->roles ? $user->roles->first()->id : null , ['class' => 'c-select select2-hidden-accessible', 'placeholder' => 'Please Select']) !!}
    </div>

    <div class="c-field u-mb-small col-lg-6">
        <label class="c-field__label" for="active">Active</label>

        {!! Form::select('active', FormPopulator::yesNo() , null , ['class' => 'c-select select2-hidden-accessible']) !!}
    </div>
</div>