<x-layouts.table>
    @php
    $title = __('Help');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>

    <x-slot:buttons>
        <!--Required to be declared--->
    </x-slot:buttons>

    <!-- Table -->
    <div class="progress-table-wrap">
        <div class="section-top-border">
            <h1 class="mb-30" style="font-size: 1.5em;">How To Apply for a Jeep Permit?</h1>
            <div class="row">
                <div class="col-md-12 mt-sm-20">
                    <p style="color: black;">Applying for a Jeep permit allows you to explore the rugged landscapes and
                        remote trails with ease. Here’s a step-by-step guide to help you get started:</p>
                    <ol>
                        <li><strong>Gather Required Documents:</strong> Prepare your identification, vehicle
                            information, and any necessary permits or licenses.</li>
                        <li><strong>Visit the Permit Office:</strong> Locate your local permit office or visit their
                            online portal to access the application form.</li>
                        <li><strong>Complete the Application Form:</strong> Fill out the application form accurately,
                            providing all required details about yourself and your vehicle.</li>
                        <li><strong>Submit Your Application:</strong> Once completed, submit your application through
                            the designated channels, ensuring all information is correct.</li>
                        <li><strong>Processing and Approval:</strong> Await processing of your application. You may
                            receive notifications regarding any additional requirements or approvals.</li>
                        <li><strong>Receive Your Permit:</strong> Upon approval, follow the instructions to receive your
                            Jeep permit. Ensure it is displayed properly in your vehicle.</li>
                    </ol>
                    <p style="color: black;">Obtaining a Jeep permit enables you to enjoy off-road adventures
                        responsibly and legally. Start your journey today!</p>
                </div>
            </div>
        </div>



    </div>
    <!-- Table ends -->



</x-layouts.table>