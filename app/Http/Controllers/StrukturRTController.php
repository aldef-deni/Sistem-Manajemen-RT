<?php

namespace App\Http\Controllers;

use App\Models\StrukturRT;
use App\Models\SettingRT;
use Illuminate\Http\Request;

class StrukturRTController extends Controller
{
    public function index()
    {
        $struktur = StrukturRT::with(['pengurus' => function($q) {
            $q->where('status', 'aktif')->orderBy('urutan');
        }])->first();

        return view('struktur-rt.index', compact('struktur'));
    }
}
