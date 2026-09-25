<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;

class TeamController extends Controller
{
    public function index()
    {
        $title = 'MapBiomas Indonesia - Team';
        $description = 'Learning from the past for the future';

        $groups = [
            'koordinator' => TeamMember::technical()->active()->where('team_group', TeamMember::GROUP_KOORDINATOR)->orderBy('sort')->get(),
            'inti' => TeamMember::technical()->active()->where('team_group', TeamMember::GROUP_INTI)->orderBy('sort')->get(),
            'regio' => TeamMember::technical()->active()->where('team_group', TeamMember::GROUP_REGIO)->orderBy('sort')->get(),
        ];

        return view('frontends.team', compact('title', 'description', 'groups'))
            ->with('collectionMax', TeamMember::collectionMaxInUse());
    }

    public function scientific()
    {
        $title = 'MapBiomas Indonesia - Team';
        $description = 'Learning from the past for the future';

        $members = TeamMember::scientific()->active()->orderBy('sort')->get();

        return view('frontends.team-scientific', compact('title', 'description', 'members'));
    }

    public function cmsIndex()
    {
        return view('cms.team.index', ['title' => 'Team']);
    }

    public function cmsCreate()
    {
        return view('cms.team.form', ['title' => 'New member']);
    }

    public function cmsEdit(int $id)
    {
        return view('cms.team.form', ['title' => 'Edit member', 'record' => $id]);
    }
}
