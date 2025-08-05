@extends('layouts.app')
@section('content')

<!-- Check if user already has hosting, if not display hosting offer, if so then display status of hosting account -->

@if ($hasHosting)

    @if(!$hosting->approved)
        <div class="border border-zinc-300 rounded-lg p-4 mt-10 text-center shadow-lg">
            <p>Thank you for requesting a hosting account from us <span class="font-bold text-lime-600">{{$hosting->created_at->diffForHumans()}}</span>.  Please note that all 
                requests are manaully reviewed.  We get to them pretty quickly but <span class="font-bold text-lime-600">it may take up to 24 hours</span> as we are UK based and you may be hours behind or ahead of our 
                active hours.
            </p>
        </div>
    @endif

    <div class="border-y border-zinc-300 my-10 p-2 font-bold text-center">
        Hosting Account Information
    </div>

    <div class="my-10 flex justify-center">
        @if ($hosting->approved)
            <button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Approved & Activated</button>
        @else
            <button class="cursor-pointer bg-orange-500 hover:bg-orange-400 text-white text-sm font-medium p-2 rounded-md transition">Under Review</button>
        @endif
    </div>

    <div class="flex flex-col md:flex-row justify-between space-x-10">
        <div class="w-full md:w-8/12 md:border-r border-zinc-300">
            <div>
                <h3 class="font-bold mb-4">cPanel Link</h3>
                <p>To access cPanel use the following link.  This is the control panel used to manage your hosting account.</p>
                <p class="mt-2 underline text-lime-800"><a href="{{ $hosting->servers->cpanelUrl }}">{{ $hosting->servers->cpanelUrl }}</a></p>
            </div>
            <div class="my-10">
                <h3 class="font-bold mb-4">Username & Password</h3>
                <p>After clicking on the link above use this username and password to log in.  You can change the password in cPanel but cannot change the username.</p>
                <div class="mt-4">
                    <table class="w-1/2 text-sm">
                        <tr class="bg-zinc-100">
                            <th>Username</th>
                            <th>Password</th>
                        </tr>
                        <tr class="text-center">
                            <td>{{ $hosting->username }}</td>
                            <td>{{ $hosting->password }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="my-10">
                <h3 class="font-bold mb-4">Nameservers</h3>
                <p>If you want to use the standard nameservers for your domain they are listed below.  Please point your domain to these.  We recommend using a CDN though, 
                    something like Cloudflare.</p>
                    <div class="mt-4">
                        <table class="w-1/2 text-sm">
                            <tr class="bg-zinc-100">
                                <th>NS1</th>
                                <th>NS2</th>
                            </tr>
                            <tr class="text-center">
                                <td>{{ $hosting->servers->ns1 }}</td>
                                <td>{{ $hosting->servers->ns2 }}</td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
        <div class="w-full md:w-4/12">
            <div class="">
                <h2 class="font-bold text-lg border-b border-zinc-300">Please follow the rules</h2>
                <p class="mt-4">There are not many rules, so it's easy to follow.  Any accounts not following the rules may be suspended without notice.</p>
                <ul class="list-inside mt-2">
                    <li class="list-disc">You must use the service to host a website</li>
                    <li class="list-disc">All websites must be in the English language</li>
                    <li class="list-disc">Legal content only</li>
                    <li class="list-disc">No mail spam</li>
                    <li class="list-disc">Idle accounts deleted after 30 days</li>
                    <li class="list-disc">1 account per member</li>
                    <li class="list-disc font-bold">No users from China or India</li>
                </ul>
                <p class="pt-2">If you break a rule we do not send any emails, your account is suspended.  It is then up to you to log in and create a support
                    ticket for more details.  If you do not your account is deleted after 7 days.</p>
            </div>
            <div class="mt-6">
                <h2 class="font-bold text-lg border-b border-zinc-300 mb-4">Support</h2>
                <p>We are largely an email free service.  If you need any support please open a ticket.</p>
                <div class="mt-4 flex justify-center">
                    <a href="/support"><button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Visit Support</button></a>
                </div>
            </div>
        </div>
    </div>

@else
    <!-- Display hosting offer as user has no hosting currently -->
    <div class="my-10">
        <h2 class="font-bold text-4xl tracking-tight text-zinc-500 text-center mb-10">Want to apply for hosting?</h2>
        <div class="my-4 w-8/12 mx-auto">
            <p class="mb-4">Before you request hosting, please read this <span class="text-lime-600 underline cursor-pointer"><a href="/about">page</a></span>, by clicking request hosting 
                below you have agreed to the rules.  No excuses to be honest, if you don't read them, don't expect any tolerance.</p>
            <p class="mb-4">If you have any questions, anything you are unsure about, please open a support ticket first.  Better to ask than have your hosting removed without warning.</p>
            <p class="mb-4">Server location is available in the form on the next page.  It changes regularly depending on available space on each server.  You can't make a specific location request.  We have what we have
                available.  We always keep the UK in stock.
            </p>
            <p class="mb-4">In the event we have no availability at all, the button below will indicate this.  If it's green and says "Request Hosting" we have availability somewhere.</p>
            <p>All requests are manually reviewed.  Once you have completed the form on the next page more details will be provided.</p>
        </div>
        <div class="flex justify-center mt-10">
            <a href="/hosting/create"><button class="cursor-pointer bg-lime-500 hover:bg-lime-400 text-white text-sm font-medium p-2 rounded-md transition">Request Hosting</button></a>
        </div>
    </div>
@endif

@endsection