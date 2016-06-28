<?php namespace flanacao\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Request;
use Response;
use Validator;
use Redirect;

class TimeController extends Controller {

// ******************************************************************
  public function lista() {
    $times = DB::select("Select * FROM times ORDER BY nome");
    return view('times.cadastro')->withTimes($times);
  }

// ******************************************************************
  public function uploadFiles() {
    // GET ALL THE INPUT DATA , $_GET,$_POST,$_FILES.
    $input = Input::only('file');
    $nome = Request::input('nome_time');
    $nacionalidade = Request::input('nacionalidade');
    
    // VALIDATION RULES
    $rules = array(
        'file' => 'image|required|mimes:png,gif,jpg,jpeg|max:300',
    );

   // PASS THE INPUT AND RULES INTO THE VALIDATOR
    $validation = Validator::make($input, $rules);

    // CHECK GIVEN DATA IS VALID OR NOT 
    if ($validation->fails()) {
        return Redirect::to('/times/cadastro')->with('erro', $validation->errors()->first());
    }
    
       $file = array_get($input,'file');
       // SET UPLOAD PATH 
        $destinationPath = public_path().'/img/upload'; 
        // GET THE FILE EXTENSION
        $extension = $file->getClientOriginalExtension(); 
        // RENAME THE UPLOAD WITH RANDOM NUMBER 
        $fileName = rand(11111, 99999) . '.' . $extension; 
        
        $image_info = getimagesize($file); //dimensões da imagem em pixels
        $image_width = $image_info[0];
        $image_height = $image_info[1];

        if ($image_width > 100 || $image_height > 100)
          return Redirect::to('/times/cadastro')->with('erro', 'Imagem deve ter no máximo 100 x 100 pixels');

        // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY 
        $upload_success = $file->move($destinationPath, $fileName); 
    
    // IF UPLOAD IS SUCCESSFUL SEND SUCCESS MESSAGE OTHERWISE SEND ERROR MESSAGE
    if ($upload_success) {
        DB::table('times')->insert(
          ['nome' => $nome, 'nacionalidade' => $nacionalidade, 'arquivo' => $fileName]
        );
        return Redirect::to('/times/cadastro')->with('message', 'Time incluído com sucesso!');
    } 
}






}
