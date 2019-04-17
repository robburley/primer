<div class="c-modal modal fade" id="searchCompletedLeads" tabindex="-1" role="dialog"
     aria-labelledby="onBoardModal" data-backdrop="static">
    <div class="c-modal__dialog modal-dialog" role="document">
        <div class="modal-content">

            <header class="c-modal__header">
                <h1 class="c-modal__title">Search Completed Leads</h1>

                <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-close"></i>
                                </span>
            </header>

            <div class="c-modal__body u-pb-small">
                {!! Form::open(['route' => 'supervisor.search.completed.index']) !!}
                <div class="row">
                    <div class="col-sm-12 u-pb-small">
                        <div class="c-field has-addon-right">
                            <label class="c-field__label">
                                Search Company Name or Phone Number
                            </label>
                        </div>

                        <div class="c-field">
                            {!! Form::text('search_term', null, ['class' => 'c-input', 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 u-pb-small">
                        <div class="c-field has-addon-right">
                            <label class="c-field__label">
                                Reason for accessing record
                            </label>
                        </div>

                        <div class="c-field">
                            {!! Form::text('search_reason', null, ['class' => 'c-input', 'required']) !!}
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="c-btn c-btn--success pull-right"
                >
                    Search
                </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>