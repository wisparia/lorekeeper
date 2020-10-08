<ul>
    <li class="sidebar-header"><a href="{{ url('world/info') }}" class="card-link">World Expanded</a></li>
    <li class="sidebar-section">
        <div class="sidebar-item"><a href="{{ url('world') }}">Encyclopedia</a></div>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Geography</div>
        <div class="sidebar-item"><a href="{{ url('world/location-types') }}" class="{{ set_active('world/location-types*') }}">Location Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/locations') }}" class="{{ set_active('world/locations*') }}">All Locations</a></div>
    </li>
    <li class="sidebar-section d-none">
        <div class="sidebar-section-header">Society~?</div>
        <div class="sidebar-item"><a href="{{ url('world/location-types') }}" class="{{ set_active('world/location-types*') }}">Location Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/locations') }}" class="{{ set_active('world/locations*') }}">All Locations</a></div>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">History</div>
        <div class="sidebar-item"><a href="{{ url('world/event-categories') }}" class="{{ set_active('world/event-categories*') }}">Event Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/events') }}" class="{{ set_active('world/events*') }}">Events</a></div>
        <div class="sidebar-item"><a href="{{ url('world/figure-categories') }}" class="{{ set_active('world/figure-categories*') }}">Figure Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/figures') }}" class="{{ set_active('world/figures*') }}"> Figures</a></div>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Nature</div>
        <div class="sidebar-item"><a href="{{ url('world/fauna-categories') }}" class="{{ set_active('world/fauna-categories*') }}">Fauna Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/faunas') }}" class="{{ set_active('world/faunas*') }}">All Fauna</a></div>
        <div class="sidebar-item"><a href="{{ url('world/flora-categories') }}" class="{{ set_active('world/flora-categories*') }}">Flora Types</a></div>
        <div class="sidebar-item"><a href="{{ url('world/floras') }}" class="{{ set_active('world/floras*') }}">All Flora</a></div>
    </li>
</ul>