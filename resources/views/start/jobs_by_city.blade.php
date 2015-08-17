@extends('layouts.default')

@section('title', 'Jobs, Praktika und mehr in '. $city->name)

@section('metadata')
    <meta name='description' content='Jobs, Praktika und mehr in ' . {{{ $city->name }}} />
    <meta name='keywords' content='jobs, praktika, stellenangebote, karriere, ' . {{{ $city->name }}} />
@stop

@section('content')
    <div class="span9 alpha">
        @if($jobs->total() == 0)
            <div id="search-results">
                <div class="alert alert-danger">
                    Für Ihre Anfrage gibt es keine Treffer!
                    <br><br>
                    {{ HTML::linkRoute('jobs', 'Alle Stellen anzeigen') }}
                </div>
            </div>
        @endif
        @if(count($jobs) > 0)
            <div id="jobs">
                <h1>Stellen in {{{ $city->name }}}</h1>
                <ul>
                    @foreach($jobs as $job)
                        <li>
                            <table>
                                <tr>
                                    <td rowspan="2" width="100px" height="50px">
                                    </td>
                                    <td colspan="3" width="400px">
                                        <span class="name">{{ HTML::linkRoute('job', $job->title, [$job->id, $job->slug]) }}</span>
                                    </td>
                                    <td width="130px">
                                        {{{ $job->location }}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <strong>Unternehmen: </strong>{{ HTML::linkRoute('unternehmen', $job->company->name, [$job->company->slug]) }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                    @endforeach
                </ul>
                @if($jobs->lastPage() <= 1)
                    <div class="pull-right">
                        <p>{{ HTML::linkRoute('jobs', 'Alle Stellen anzeigen', [], ['class' => 'green']) }}</p>
                    </div>
                @endif

                {{ $jobs->setPath('')->appends(Request::except('page'))->render(new \Illuminate\Pagination\SimpleBootstrapThreePresenter($jobs)) }}
            </div>
        @endif
    </div>

    <div class="span3 omega">

        @include('start/partials/searchbox_jobs')

        <div id="job-type-search">
            <h3>Angebote für</h3>
            <ul>
                <li {{ Request::get('typ') == 'Vollzeit' ? 'class=active' : '' }} >
                    {{ HTML::linkRoute('jobsIn', 'Vollzeit',[urldecode(Request::segment(3)), 'typ' => 'Vollzeit']) }}
                </li>
                <li {{ Request::get('typ') == 'Teilzeit' ? 'class=active' : '' }} >
                    {{ HTML::linkRoute('jobsIn', 'Teilzeit',[urldecode(Request::segment(3)), 'typ' => 'Teilzeit']) }}
                </li>
                <li {{ Request::get('typ') == 'Praktikum' ? 'class=active' : '' }} >
                    {{ HTML::linkRoute('jobsIn', 'Praktikum',[urldecode(Request::segment(3)), 'typ' => 'Praktikum']) }}
                </li>
                <li {{ Request::get('typ') == 'Werkstudent' ? 'class=active' : '' }} >
                    {{ HTML::linkRoute('jobsIn', 'Werkstudent',[urldecode(Request::segment(3)), 'typ' => 'Werkstudent']) }}
                </li>
                <li {{ Request::get('typ') == 'Abschlussarbeit' ? 'class=active' : '' }} >
                    {{ HTML::linkRoute('jobsIn', 'Abschlussarbeit',[urldecode(Request::segment(3)), 'typ' => 'Abschlussarbeit']) }}
                </li>
            </ul>
        </div>
    </div>
@stop