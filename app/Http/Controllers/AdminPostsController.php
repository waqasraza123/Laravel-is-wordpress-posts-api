<?php namespace App\Http\Controllers;

	use App\Post;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Input;
    use Session;
//	use Request;
	use DB;
	use CRUDBooster;

	use GuzzleHttp\Exception\GuzzleException;
	use GuzzleHttp\Client;
    use App\Http\HttpRequests;


	class AdminPostsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon_text";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "posts";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Image","name"=>"image","image"=>true];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Publish At (GMT)","name"=>"publish_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Image','name'=>'image','type'=>'upload','validation'=>'required|image|max:20480','width'=>'col-sm-10','help'=>'File types support : JPG, JPEG, PNG, GIF, BMP'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'publish|Publish;draft|Draft'];
			$this->form[] = ['label'=>'Publish To','name'=>'categories','type'=>'checkbox','validation'=>'required|min:1|max:5000','width'=>'col-sm-10','datatable'=>'sites,name'];
            $this->form[] = ['label'=>'Category','name'=>'tags','type'=>'checkbox','validation'=>'required|min:1|max:5000','width'=>'col-sm-10','datatable'=>'topics,name'];
//            $this->form[] = ['label'=>'Tags','name'=>'tags','type'=>'custom','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10','html'=>'<input type="text" name="tags" id="tags" class="form-control" data-role="tagsinput" />'];
			$this->form[] = ['label'=>'Publish At (GMT)','name'=>'publish_at','type'=>'datetime','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Content','name'=>'content','type'=>'wysiwyg','validation'=>'required|string|min:5|max:10000','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Image','name'=>'image','type'=>'upload','validation'=>'required|image|max:20480','width'=>'col-sm-10','help'=>'File types support : JPG, JPEG, PNG, GIF, BMP'];
			//$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'publish|Publish;draft|Draft'];
			//$this->form[] = ['label'=>'Categories','name'=>'categories','type'=>'checkbox','validation'=>'required|min:1|max:5000','width'=>'col-sm-10','datatable'=>'sites,name'];
			//$this->form[] = ['label'=>'Tags','name'=>'tags','type'=>'custom','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10','html'=>'<input type="text" name="tags" id="tags" class="form-control" data-role="tagsinput" />'];
			//$this->form[] = ['label'=>'Publish At','name'=>'publish_at','type'=>'datetime','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Content','name'=>'content','type'=>'wysiwyg','validation'=>'required|string|min:5|max:10000','width'=>'col-sm-10'];
			# OLD END FORM

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
	        $this->addaction = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert        = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js[] = asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js");



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = ".bootstrap-tagsinput {width: 100% !important;}";



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css[] = asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css");


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
			$this->hook_after_edit($id);

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */



	    public function hook_after_edi2t($id) {
            $post = DB::table('posts')->where('id',$id)->first();

            //return if the post status is not publish
            if($post->status != "publish")
                return;

            $postAuthHandler = new PostsAuthController();
            $postHandler = new PostsHandlerController();
            $sessionId = null;
            $image  = DB::table('posts')->where('id',$id)->pluck('image');

            $data = array();
			$data["status"] = "publish";
			$data["title"] = $post->name;
			$data["content"] = $post->content;
			$data["date_gmt"] = $post->publish_at;
			$data["source_url"] = $post->image;
			// $data["tags"] = explode(",", $post->tags);

            //categories are websites
			$categories = explode(";", $post->categories);
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }


        /**
         *
         * handles the post save request
         * overrides the crudbooster method
         * for saving the post
         *
         */
        public function postAddSave() {
            $destinationPath = '\uploads';
	        //save the image
            $originalNameWithExtention = Input::file('image')->getClientOriginalName();
            $fileName = Carbon::now()->format('d-m-Y').str_replace(" ", "_", strtolower($originalNameWithExtention)); // renameing image
            Input::file('image')->move(public_path() . $destinationPath, $fileName);
            $post = new Post();
            $post->name = $_POST['name'];
            $post->image = $fileName;
            $post->status = $_POST['status'];
            $post->content = $_POST['content'];
            $post->publish_at = $_POST['publish_at'];

            $post->save();
            $post->sites()->sync($_POST['categories']);
            $post->topics()->sync($_POST['tags']);

            if($post->status == 'draft')
                return;

            $tags = ($post->topics()->pluck('name')->toArray());
            $tags = implode (", ", $tags);
            $categories = $post->sites;
            $postAuthHandler = new PostsAuthController();
            $postHandler = new PostsHandlerController();
            $sessionId = null;

            foreach($categories as $site){
                $sessionId = $postAuthHandler->login($site->username, $site->password, $site->url);
                $imageId = $postHandler->uploadImage($sessionId, 'uploads/' . $post->image, $site->url);
                $postHandler->createPost($sessionId, $post->name, $post->content, $imageId, $post->publish_at, $post->status, $tags, $site->url);
            }

            return redirect()->back()->withSuccess("Post Inserted Successfully");
        }

        public function postEditSave($id){

            $post = Post::find($id);
            $post->name = $_POST['name'];
            $post->status = $_POST['status'];
            $post->content = $_POST['content'];
            $post->publish_at = $_POST['publish_at'];

            if(!empty(Input::file('image'))){
                $destinationPath = '\uploads';
                //save the image
                $originalNameWithExtention = Input::file('image')->getClientOriginalName();
                $fileName = Carbon::now()->format('d-m-Y').str_replace(" ", "_", strtolower($originalNameWithExtention)); // renameing image
                Input::file('image')->move(public_path() . $destinationPath, $fileName);
                $post->image = $fileName;
            }

            $post->save();
            $post->sites()->sync($_POST['categories']);
            $post->topics()->sync($_POST['tags']);

            if($post->status == 'draft')
                return redirect('/admin/posts');

            $tags = ($post->topics()->pluck('name')->toArray());
            $tags = implode (", ", $tags);
            $categories = $post->sites;
            $postAuthHandler = new PostsAuthController();
            $postHandler = new PostsHandlerController();
            $sessionId = null;

            foreach($categories as $site){
                $sessionId = $postAuthHandler->login($site->username, $site->password, $site->url);
                $imageId = $postHandler->uploadImage($sessionId, 'uploads/' . $post->image, $site->url);
                $postHandler->createPost($sessionId, $post->name, $post->content, $imageId, $post->publish_at, $post->status, $tags, $site->url);
            }

            return redirect('/admin/posts')->withSuccess("Post Inserted Successfully");
        }
}
