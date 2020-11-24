<?php

namespace App\Http\Controllers;

use App\Category;
use App\Machine;
use App\MachineReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_title'] = "Machine List";
        $data['machine'] = Machine::orderBy('id','desc')->get();
        return view("machine.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = "Create new machine";
        $data['products'] = Category::find(1)->products;
        return view("machine.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:machines',
            'code' => 'required|unique:machines',
            'product_id' => 'required',
            'current_reading' => 'required|numeric'
        ]);

        $in = $request->except(['_method','_token']);
        Machine::create($in);
        session()->flash('message','Machine Add Successfully');
        session()->flash('type','success');
        return redirect()->route('machine.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): \Illuminate\Http\Response
    {
        $data['page_title'] = "Edit machine";
        $data['products'] = Category::find(1)->products;
        $data['machine'] = Machine::findOrFail($id);
        return view("machine.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): \Illuminate\Http\Response
    {
        $machine = Machine::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:machines,name,'.$id,
            'code' => 'required|unique:machines,code,'.$id,
            'product_id' => 'required',
            'current_reading' => 'required|numeric'
        ]);

        $in = $request->except(['_method','_token']);
        $machine->update($in);
        session()->flash('message','Machine Update Successfully');
        session()->flash('type','success');
        return redirect()->route('machine.index');
    }

    public function activation(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required',
            'current_reading' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($request->input('id'));
            if ($request->input('type')){
                //Run
                $reading = new MachineReading();
                $reading->machine_id = $machine->id;
                $reading->start_reading = $request->input('current_reading');
                $reading->save();

                $machine->current_reading = $request->input('current_reading');
                $machine->activated = true;
                $machine->save();
            }else{
                // Stop machine
                $reading = MachineReading::whereMachine_id($machine->id)->whereEnd_reading(null)->first();
                $reading->end_reading = $request->input('current_reading');
                $reading->save();

                $machine->current_reading = $request->input('current_reading');
                $machine->activated = false;
                $machine->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $e->getMessage();
            abort(503);
        }
        session()->flash('message','Machine Reading updated');
        session()->flash('type','success');
        return redirect()->back();
    }

    public function reading()
    {
        $data['page_title'] = "Machine Reading List";
        $data['lists'] = MachineReading::orderBy('id', 'desc')->get();
        return view("machine.reading", $data);
    }

}
