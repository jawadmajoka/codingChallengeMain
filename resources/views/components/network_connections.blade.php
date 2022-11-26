<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <form name="connection_tabs">
                    <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="suggestions_div" autocomplete="off"
                               checked>
                        <label class="btn btn-outline-primary" for="suggestions_div" id="get_suggestions_btn">Suggestions
                            (<span id="suggesion_count"></span>)</label>

                        <input type="radio" class="btn-check" name="btnradio" id="request_sent_div" autocomplete="off">
                        <label class="btn btn-outline-primary" for="request_sent_div" id="get_sent_requests_btn">Sent
                            Requests
                            (<span id="sent_request_count"></span>)</label>

                        <input type="radio" class="btn-check" name="btnradio" id="request_received_div"
                               autocomplete="off">
                        <label class="btn btn-outline-primary" for="request_received_div"
                               id="get_received_requests_btn">Received
                            Requests(<span id="received_request_count"></span>)</label>

                        <input type="radio" class="btn-check" name="btnradio" id="connection_div" autocomplete="off">
                        <label class="btn btn-outline-primary" for="connection_div" id="get_connections_btn">Connections
                            (<span id="connection_count"></span>)</label>
                    </div>
                </form>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>
                <div id="component_parent">
                    <div class="compenent_container" id="suggestion_com">
                        <x-suggestion/>
                    </div>
                    <div class="compenent_container" id="request_com">
                        <x-request/>
                    </div>
                    <div class="compenent_container" id="request_received_com">
                        <x-request_received/>
                    </div>
                    <div class="compenent_container" id="connection_com">
                        <x-connection/>
                    </div>
                </div>
            </div>

            {{-- Remove this when you start working, just to show you the different components --}}
            {{--<span class="fw-bold">Sent Request Blade</span>
            <x-request :mode="'sent'" />

            <span class="fw-bold">Received Request Blade</span>
            <x-request :mode="'received'" />

            <span class="fw-bold">Suggestion Blade</span>
            <x-suggestion />

            <span class="fw-bold">Connection Blade (Click on "Connections in common" to see the connections in common
              component)</span>
            <x-connection />--}}
            {{-- Remove this when you start working, just to show you the different components --}}

            {{--<div id="skeleton" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                <x-skeleton />
              @endfor
            </div>

            <span class="fw-bold">"Load more"-Button</span>
            <div class="d-flex justify-content-center mt-2 py-3 --}}{{-- d-none --}}{{--" id="load_more_btn_parent">
              <button class="btn btn-primary" onclick="" id="load_more_btn">Load more</button>
            </div>--}}
        </div>
    </div>
</div>

{{-- Remove this when you start working, just to show you the different components --}}

<div id="connections_in_common_skeleton" class="d-none">
    <br>
    <span class="fw-bold text-white">Loading Skeletons</span>
    <div class="px-2">
        @for ($i = 0; $i < 10; $i++)
            <x-skeleton/>
        @endfor
    </div>
</div>
