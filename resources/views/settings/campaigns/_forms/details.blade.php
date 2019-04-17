<div class="row">
    <div class="col-12">
        <h3 class="u-h4">
            Details
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <p>
            Name the campaign to be easily identifiable and deactivate if you want to stop agents processing these
            leads.
        </p>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="c-field u-mb-small col-12">
                <label class="c-field__label" for="first_name">Name</label>

                {!! Form::text('name', null, ['class' => 'c-input', 'placeholder' => 'Campaign Name', 'id' => 'name']) !!}
            </div>

            <div class="c-field u-mb-small col-12">
                <label class="c-field__label" for="active">Active</label>

                {!! Form::select('active', FormPopulator::yesNo() , null , ['class' => 'c-select select2-hidden-accessible']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h3 class="u-h4">
            Data
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <p>Options regarding how we want to process data. Choosing confirm leads before sending will add a new
            confirmation process between confirmers and agents. </p>
        <p>Processing completed leads:</p>
        <p>None - Completed leads stay in the system. Great for data cleansing.</p>
        <p>Email - Completed leads will be sent by email</p>
        <p>API - We will send leads to and API endpoint of another system.</p>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="c-field u-mb-small col-12">
                <label class="c-field__label" for="active">Confirm Leads before sending?</label>

                {!! Form::select('validate_leads', FormPopulator::yesNo() , null , ['class' => 'c-select select2-hidden-accessible']) !!}
            </div>

            <div class="c-field u-mb-small col-12   ">
                <label class="c-field__label" for="active">Order of Leads presented?</label>

                {!! Form::select('lead_order', FormPopulator::leadOrderingTypes() , null , ['class' => 'c-select select2-hidden-accessible']) !!}
            </div>
            <div class="c-field u-mb-small col-12   ">
                <label class="c-field__label" for="active">How to process completed leads?</label>

                {!! Form::select('endpoint_type', FormPopulator::campaignEndpoints() , null , ['class' => 'c-select select2-hidden-accessible']) !!}
            </div>

            <div class="c-field u-mb-small col-12   ">
                <label class="c-field__label" for="first_name">Where will completed leads go to?</label>

                {!! Form::text('endpoint_location', null, ['class' => 'c-input', 'placeholder' => 'primerapp.co.uk/api/leads', 'id' => 'endpoint_location']) !!}
            </div>
        </div>
    </div>
</div>
