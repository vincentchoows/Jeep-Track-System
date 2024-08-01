<!-- NOTE:-
-
- components/layouts/app.blade.php Template 
-
-->


<x-layouts.app>
    <x-slot:title>
        {{__('Dashboard')}}
    </x-slot:title>

    <div class="col-xl-10 col-lg-10 col-md-6 col-sm-6">
        <div style="display: flex; justify-content: space-between;border-bottom: 1px solid black;">
            <h3 class="mb-15 mt-15">My Permits</h3>

            <button style="margin-bottom:0.5em; width:20em;" class="genric-btn danger success-border small">Apply New
                Permit</button>
        </div>
        <div class="progress-table-wrap">
            <div class="progress-table">
                <div class="table-head">
                    <div class="serial">#</div>
                    <div class="country">Countries</div>
                    <div class="visit">Visits</div>
                    <div class="visit">Visits</div>

                    <div class="visit">Action</div>
                </div>
                <div class="table-row">
                    <div class="serial">01</div>
                    <div class="country"> <img src="assets/img/elements/f1.jpg" alt="flag">Canada</div>
                    <div class="visit">645032</div>
                    <div class="visit">645032</div>

                    <div class="action">
                        <button class="edit-button"><i class="fas fa-edit"></i></button>
                        <button class="view-button"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="table-row">
                    <div class="serial">02</div>
                    <div class="country"> <img src="assets/img/elements/f2.jpg" alt="flag">Canada</div>
                    <div class="visit">645032</div>
                    <div class="visit">645032</div>

                    <div class="action">
                        <button class="edit-button"><i class="fas fa-edit"></i></button>
                        <button class="view-button"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="table-row">
                    <div class="serial">03</div>
                    <div class="country"> <img src="assets/img/elements/f3.jpg" alt="flag">Canada</div>
                    <div class="visit">645032</div>

                    <div class="visit">645032</div>
                    <div class="action">
                        <button class="edit-button"><i class="fas fa-edit"></i></button>
                        <button class="view-button"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="table-row">
                    <div class="serial">04</div>
                    <div class="country"> <img src="assets/img/elements/f4.jpg" alt="flag">Canada</div>
                    <div class="visit">645032</div>

                    <div class="visit">645032</div>
                    <div class="action">
                        <button class="edit-button"><i class="fas fa-edit"></i></button>
                        <button class="view-button"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="table-row">
                    <div class="serial">05</div>
                    <div class="country"> <img src="assets/img/elements/f5.jpg" alt="flag">Canada</div>
                    <div class="visit">645032</div>

                    <div class="visit">645032</div>
                    <div class="action">
                        <button class="edit-button"><i class="fas fa-edit"></i></button>
                        <button class="view-button"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    </x-app-layout>