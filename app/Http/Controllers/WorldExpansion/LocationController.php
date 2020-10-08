<?php

namespace App\Http\Controllers\WorldExpansion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Settings;
use App\Models\SitePage;

use App\Models\WorldExpansion\FaunaCategory;
use App\Models\WorldExpansion\FloraCategory;
use App\Models\WorldExpansion\EventCategory;
use App\Models\WorldExpansion\Location;
use App\Models\WorldExpansion\LocationType;


class LocationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Location Controller
    |--------------------------------------------------------------------------
    |
    | This controller shows locations and their types, as well as the 
    | main World Info page created in the World Expansion extension.
    |
    */
    
    /**
     * Shows the locations page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getWorld()
    {
        $world = SitePage::where('key','world')->first();
        if(!$world) abort(404);

        return view('worldexpansion.world', [
            'world' => $world
        ]);
    }

    /**
     * Shows the index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('world.index');
    }

    /**
     * Shows the location types page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLocationTypes(Request $request)
    {
        $query = LocationType::query();
        $name = $request->get('name');
        if($name) $query->where('name', 'LIKE', '%'.$name.'%');
        return view('worldexpansion.location_types', [  
            'types' => $query->orderBy('sort', 'DESC')->paginate(20)->appends($request->query())

        ]);
    }
    
    /**
     * Shows the locations page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLocationType($id)
    {
        $type = LocationType::where('is_active',1)->find($id);
        if(!$type) abort(404);

        return view('worldexpansion.location_type_page', [
            'type' => $type
        ]);
    }
    
    /**
     * Shows the locations page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLocations(Request $request)
    {
        $query = Location::with('type');
        $data = $request->only(['type_id', 'name', 'sort']);
        if(isset($data['type_id']) && $data['type_id'] != 'none') 
            $query->where('type_id', $data['type_id']);
        if(isset($data['name'])) 
            $query->where('name', 'LIKE', '%'.$data['name'].'%');

        if(isset($data['sort'])) 
        {
            switch($data['sort']) {
                case 'alpha':
                    $query->sortAlphabetical();
                    break;
                case 'alpha-reverse':
                    $query->sortAlphabetical(true);
                    break;
                case 'type':
                    $query->sortLocationType();
                    break;
                case 'newest':
                    $query->sortNewest();
                    break;
                case 'oldest':
                    $query->sortOldest();
                    break;
            }
        } 
        else $query->sortLocationType();

        return view('worldexpansion.locations', [
            'locations' => $query->paginate(20)->appends($request->query()),
            'types' => ['none' => 'Any Type'] + LocationType::orderBy('sort', 'DESC')->pluck('name', 'id')->toArray(),
            'loctypes' => LocationType::where('is_active',1)->get(),
            'user_enabled' => Settings::get('WE_user_locations'),
            'ch_enabled' => Settings::get('WE_character_locations')
        ]);
    }
    
    /**
     * Shows the locations page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLocation($id)
    {
        $location = Location::where('is_active',1)->find($id);
        if(!$location) abort(404);

        return view('worldexpansion.location_page', [
            'location' => $location,
            'user_enabled' => Settings::get('WE_user_locations'),
            'loctypes' => LocationType::where('is_active',1)->get(),
            'ch_enabled' => Settings::get('WE_character_locations'),
            'fauna_categories' => FaunaCategory::where('is_active',1)->get(),
            'flora_categories' => FloraCategory::where('is_active',1)->get(),
            'event_categories' => EventCategory::where('is_active',1)->get(),
        ]);
    }
    
}
