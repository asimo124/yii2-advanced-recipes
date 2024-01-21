<?php

namespace frontend\controllers;

use frontend\models\RiProtein;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class TopRecipesController extends Controller
{
    public $enableCsrfValidation = false;
    //*/
    private $allowedOriginDomain = "https://recipes.hawleywebdesign.com";
    /*/
    private $allowedOriginDomain = "http://localhost:4200";
    //*/

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['top-recipes', 'ingredients-by-price', 'shopping-list'],
                    'allow' => true,
                    'roles' => ['@', '?']
                ],
                [
                    'actions' => ['shopping-list'],
                    'allow' => true,
                    'roles' => ['@']
                ],
            ],
        ];

        //$behaviors['authenticator']['except'] = ['top-recipes'];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIngredientsByPrice()
    {
        $sql = "SELECT 
                ri.id, ri.title, ri.cheap_price as price 
                FROM ri_ingredient ri 
                order by ri.cheap_price DESC ";

        $query = Yii::$app->db->createCommand($sql);
        $results = $query->queryAll();

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "items" => $results
        ]);
        die();
    }

    public function actionShoppingList()
    {
        $request = Yii::$app->request;

        $recipeIds = $request->post("recipes", []);

        ## Prepared Statements ##
        $sql = "SELECT p.id as protein_id, p.title as protein, p.cheap_price 
                FROM ri_recipe r 
                INNER JOIN ri_protein p 
                    ON r.protein_id = p.id 
                WHERE r.id = :recipe_id ";
        $stmt_sel_protiens = Yii::$app->db->createCommand($sql);

        $sql = "SELECT i.id, i.title as ingredient, i.cheap_price, ss.title as store_section
                FROM ri_recipe_ingredient ri 
                INNER JOIN ri_ingredient i 
                    ON ri.ingredient_id = i.id 
                LEFT JOIN ri_store_section ss 
                    ON i.store_section_id = ss.id 
                WHERE ri.recipe_id = :recipe_id ";
        $stmt_sel_ingredients = Yii::$app->db->createCommand($sql);

        $sql = "SELECT hi.ingredient_id FROM ri_home_inventory hi WHERE hi.ingredient_id = :ingredient_id ";
        $stmt_sel_home_inventory = Yii::$app->db->createCommand($sql);
        ## ##

        $sql = "SELECT ss.id, ss.title, ss.sort_order 
                FROM ri_store_section ss ";
        $storeSectionsQuery = Yii::$app->db->createCommand($sql);

        $storeSectionsArr = [];
        $storeSections = $storeSectionsQuery->queryAll();
        foreach ($storeSections as $getSection) {
            $storeSectionsArr[$getSection['title']] = $getSection['sort_order'];
        }

        $ingredients = [];
        $recipeProteins = [];
        foreach ($recipeIds as $getRecipeId) {

            $sql = "SELECT i.id, i.title as ingredient, i.cheap_price, ss.title as store_section
                FROM ri_recipe_ingredient ri 
                INNER JOIN ri_ingredient i 
                    ON ri.ingredient_id = i.id 
                LEFT JOIN ri_store_section ss 
                    ON i.store_section_id = ss.id 
                WHERE ri.recipe_id = :recipe_id ";
            $stmt_sel_ingredients->bindParam(':recipe_id', $getRecipeId);
            $Ingreds = $stmt_sel_ingredients->queryAll();

            foreach ($Ingreds as $getIngred) {
                if (!isset($ingredients[$getIngred['store_section']])) {
                    $ingredients[$getIngred['store_section']] = [];
                }

                $sql = "SELECT hi.ingredient_id FROM ri_home_inventory hi WHERE hi.ingredient_id = :ingredient_id ";
                $stmt_sel_home_inventory->bindParam(':ingredient_id', $getIngred['id']);
                $HasHomeInventory = $stmt_sel_home_inventory->queryOne();

                if (!$HasHomeInventory) {
                    if (!isset($ingredients[$getIngred['store_section']][$getIngred['ingredient']])) {
                        $ingredients[$getIngred['store_section']][$getIngred['ingredient']] = [
                            "count" => 0,
                            "price" => $getIngred['cheap_price']
                        ];
                    }
                    $ingredients[$getIngred['store_section']][$getIngred['ingredient']]['count'] += 1;
                }
            }

            $sql = "SELECT p.id as protein_id, p.title as protein, p.cheap_price 
                    FROM ri_recipe r 
                    INNER JOIN ri_protein p 
                        ON r.protein_id = p.id 
                    WHERE r.id = :recipe_id ";
            $stmt_sel_protiens->bindParam(':recipe_id', $getRecipeId);
            $Recipe = $stmt_sel_protiens->queryOne();
            if ($Recipe) {
                if (!isset($recipeProteins[$Recipe['protein']])) {
                    $recipeProteins[$Recipe['protein']] = [
                        "count" => 0,
                        "price" => $Recipe['cheap_price']
                    ];
                }
                $recipeProteins[$Recipe['protein']]['count'] += 1;
            }
        }

        $ingredientSorted = [];
        foreach ($ingredients as $storeSection => $ingredQuantity) {

            $useIndex = 0;
            if (isset($storeSectionsArr[$storeSection])) {
                $useIndex = $storeSectionsArr[$storeSection];
            }
            $ingredientSorted[$useIndex] = $ingredQuantity;
        }
        $ingredients = $ingredientSorted;

        $items = [];
        foreach ($ingredients as $storeSection => $ingredTitleItem) {
            foreach ($ingredTitleItem as $ingredientTitle => $item) {
                $eachItem = [
                    "count" => $item['count'],
                    "title" => $ingredientTitle,
                    "price" => intval($item['price'])
                ];
                $items[] = $eachItem;
            }
            $items[] = [
                "count" => 0,
                "title" => "",
                "price" => 0
            ];
        }
        foreach ($recipeProteins as $proteinTitle => $item) {
            $eachItem = [
                "count" => $item['count'],
                "title" => $proteinTitle,
                "price" => intval($item['count'] * $item['price'])
            ];
            $items[] = $eachItem;
        }

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "items" => $items/*,
            "ingredients" => $ingredients,
            "recipeProteins" => $recipeProteins*/
        ]);
        die();
    }

    public function actionTopRecipes()
    {
        $request = Yii::$app->request;
        $protein_ids = $request->get("protein_ids", []);

        $proteinId = $request->get("protein_id", 0);
        $difficulty_level_id = $request->get("difficulty_level_id", 0);
        $taste_level_id = $request->get("taste_level_id", 0);
        $recipe_style_id = $request->get("recipe_style_id", 0);
        $sort1 = $request->get("sort1", "");
        $sort_dir1 = $request->get("sort_dir1", "ASC");
        $sort2 = $request->get("sort2", "");
        $sort_dir2 = $request->get("sort_dir2", "ASC");
        $sort3 = $request->get("sort3", "");
        $sort_dir3 = $request->get("sort_dir3", "ASC");

        if ($proteinId) {
            $protein_ids = [$proteinId];
        }
        if ($protein_ids == [0]) {
            $protein_ids = [];
        }

        $whereSql = '';
        if (count($protein_ids) > 0) {
            $whereSql .= "AND r.protein_id IN (" . implode(",", $protein_ids) . ") 
            ";
        }
        if ($difficulty_level_id) {
            $whereSql .= "AND dl.id = :difficulty_level_id 
            ";
        }
        if ($recipe_style_id) {
            $whereSql .= "AND rs.id = :recipe_style_id 
            ";
        }
        if ($taste_level_id) {
            $whereSql .= "AND tl.id = :taste_level_id 
            ";
        }

        $sortSql = $this->getSortSql($sort1, $sort2, $sort3, $sort_dir1, $sort_dir2, $sort_dir3);

        $sql = "SELECT r.id
                , r.title as recipe
                , p.title as protein
                , rs.title as style
                , (
                    SUM(
                        CASE WHEN hi.id IS NULL THEN 
                            i.cheap_price
                        ELSE
                            0
                        END
                    ) 
                    + p.cheap_price) as recipe_low_price
                , (
                    SUM(
                        CASE WHEN hi.id IS NULL THEN 
                            i.price
                        ELSE
                            0
                        END
                    ) 
                    + p.price) as recipe_high_price
                , dl.title as difficulty_level
                , tl.title as taste_level
                , r.last_date_made
                , r.image_path
                FROM ri_recipe r 
                INNER JOIN ri_protein p 
                    ON r.protein_id = p.id 
                INNER JOIN ri_recipe_style rs 
                    oN r.recipe_style_id = rs.id 
                INNER JOIN ri_difficulty_level dl 
                    oN r.difficulty_level_id = dl.id 
                INNER JOIN ri_taste_level tl 
                    ON r.taste_level_id = tl.id 
                INNER JOIN ri_recipe_ingredient ri 
                    ON r.id = ri.recipe_id 
                INNER JOIN ri_ingredient i 
                    oN ri.ingredient_id = i.id 
                LEFT JOIN ri_home_inventory hi 
                    oN i.id = hi.ingredient_id
                WHERE 1 
                AND r.is_deleted = 0

                $whereSql
                
                GROUP BY r.id
                
                $sortSql
                ";

        $query = Yii::$app->db->createCommand($sql);

        if ($difficulty_level_id) {
           $query->bindParam(':difficulty_level_id', $difficulty_level_id);
        }
        if ($recipe_style_id) {
            $query->bindParam(':recipe_style_id', $recipe_style_id);
        }
        if ($taste_level_id) {
            $query->bindParam(':taste_level_id', $taste_level_id);
        }

        /*/
        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");
        echo json_encode([
            "new_sql" => $sql,
            "params" => [
                ":difficulty_level_id" => $difficulty_level_id,
                ":recipe_style_id" => $recipe_style_id,
                ":taste_level_id" => $taste_level_id,
            ]
        ]);
        die();
        //*/

        $recipes = $query->queryAll();

        ## Prepared Statements ##
        $sql = "SELECT * FROM ri_recipe_ingredient WHERE recipe_id = :recipe_id ";
        $stmt_sel_recipe_ingredients = Yii::$app->db->createCommand($sql);
        ## ##

        foreach ($recipes as $index => $getRecipe) {

            $sql = "SELECT * FROM ri_recipe_ingredient WHERE recipe_id = :recipe_id ";
            $stmt_sel_recipe_ingredients->bindParam(':recipe_id', $getRecipe['id']);
            $recipeIngredients = $stmt_sel_recipe_ingredients->queryAll();
            $recipes[$index]['riRecipeIngredients'] = $recipeIngredients;
        }

        $proteins = RiProtein::find()->asArray()->all();
        foreach ($proteins as $index => $getProtein) {
            $proteins[$index]['selected'] = 0;
            if (in_array($getProtein['id'], $protein_ids)) {
                $proteins[$index]['selected'] = 1;
            }
        }

        header("Access-Control-Allow-Origin: {$this->allowedOriginDomain}");
        header("Content-type: application/json");

        echo json_encode([
            "items" => $recipes
            /*"proteins" => $proteins,*/
        ]);
        die();
    }

    private function getSortSql($sort1, $sort2, $sort3, $sort_dir1, $sort_dir2, $sort_dir3) {

        $sortSql = "";
        if ($sort1) {
            if ($sort1 == "taste_level") {
                if ($sort_dir1 == "ASC") {
                    $sort_dir1 = "DESC";
                } else {
                    $sort_dir1 = "ASC";
                }
            }
            $sortName = "";
            switch ($sort1) {
                case "cheap_price":
                    $sortName = "recipe_low_price";
                    break;
                case "price":
                    $sortName = "recipe_low_price";
                    break;
                case "taste_level":
                    $sortName = "tl.id";
                    break;
                case "difficulty_level":
                    $sortName = "dl.id";
                    break;
            }
            $sortSql = "ORDER BY $sortName $sort_dir1";
        }
        if ($sort2) {
            if ($sort2 == "taste_level") {
                if ($sort_dir2 == "ASC") {
                    $sort_dir2 = "DESC";
                } else {
                    $sort_dir2 = "ASC";
                }
            }
            $sortName = "";
            switch ($sort2) {
                case "cheap_price":
                    $sortName = "recipe_low_price";
                    break;
                case "price":
                    $sortName = "recipe_low_price";
                    break;
                case "taste_level":
                    $sortName = "tl.id";
                    break;
                case "difficulty_level":
                    $sortName = "dl.id";
                    break;
            }
            if (!$sortSql) {
                $sortSql = "ORDER BY $sortName $sort_dir2";
            } else {
                $sortSql .= ", $sortName $sort_dir2";
            }
        }
        if ($sort3) {
            if ($sort3 == "taste_level") {
                if ($sort_dir3 == "ASC") {
                    $sort_dir3 = "DESC";
                } else {
                    $sort_dir3 = "ASC";
                }
            }
            $sortName = "";
            switch ($sort3) {
                case "cheap_price":
                    $sortName = "recipe_low_price";
                    break;
                case "price":
                    $sortName = "recipe_low_price";
                    break;
                case "taste_level":
                    $sortName = "tl.id";
                    break;
                case "difficulty_level":
                    $sortName = "dl.id";
                    break;
            }
            if (!$sortSql) {
                $sortSql = "ORDER BY $sortName $sort_dir3";
            } else {
                $sortSql .= ", $sortName $sort_dir3";
            }
        }
        if ($sortSql) {
            $sortSql .= ", r.title ASC ";
        }
        return $sortSql;
    }
}
