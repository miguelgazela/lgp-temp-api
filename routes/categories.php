<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/categories', 'getCategories');
    $app->get('/categories/:id', 'getCategory');
    $app->post('/categories', 'createCategory');
    $app->put('/categories/:id', 'updateCategory');
    $app->delete('/categories/:id', 'deleteCategory');
    $app->get('/get_categories', 'getCategoriesForAndroid');


    // FUNCTIONS -->

    function getCategories() {
        setHeaders();
        $categories["categories"] = Category::all()->toArray();
        returnJson($categories);
    }

    function getCategory($id) {
        setHeaders();
        
        $result["error"] = false;
        $cat = Category::find($id);

        if($cat != null) {
            $result["category"] = $cat->toArray();
        } else {
            $result["error"] = true;
            $result["message"] = "Category does not exist";
        }

        returnJson($result);
    }

    function createCategory() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;
        if(isset($data['name'])) {
            $name = $data['name'];
            $image = $data['image'];

            $found_results = Category::where('name', '=', $name)->get()->toArray();

            if(!empty($found_results)) {
                $result["error"] = true;
                $result["message"] = "Category ".$name." already exists";
            } else {
                $cat = new Category;
                $cat->name = $name;
                if($image != null && $image != "") {
                    $cat->image_path = $image;
                }

                $cat->save();

                $result["category"] = Category::find($cat->id)->toArray();
            }
        }

        returnJson($result);
    }

    function updateCategory($id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;
        $cat = Category::find($id);

        if($cat != null) {
            $name = $data["name"];
            $image = $data["image"];

            if($name != null) {
                $cat->name = $name;
            }

            if($image != null) {
                $cat->image_path = $image;
            }

            $cat->save();
            $result["category"] = $cat->toArray();
        } else {
            $result["error"] = true;
            $result["message"] = "Category does not exist";
        }

        returnJson($result);
    }

    function deleteCategory($id) {
        setHeaders();
        
        $result["error"] = false;
        $cat = Category::find($id);

        if($cat != null) {
            $cat->delete();
        } else {
            $result["error"] = true;
            $result["message"] = "Category does not exist";
        }

        returnJson($result);
    }

    function getCategoriesForAndroid() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $ret["error"] = "000";

        if(!isRequestValid($app)) {
          $ret["error"] = "001";
        } else {
          $ret["categories"] = Category::all()->toArray();
        }
        
        returnJson($ret);
    }
?>