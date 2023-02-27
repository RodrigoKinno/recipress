<?php
/*
Plugin Name: Reciperie
Author: Schematize
Author URI: https://www.schematize.com.br/
Version: 1.0.0
Description: Gerador de Post Type para Loops de páginas de Aterrissagem
*/

function create_reciperie_post_type() {
    register_post_type( 'recipe',
        array(
            'labels' => array(
                'name' => __( 'Recipe' ),
                'singular_name' => __( 'Recipe' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title' ),
           // 'menu_icon' => plugin_dir_url(__FILE__) . 'icon.jpeg', 
            
        )
    );
    
    // Fields personalizados usando ACF.
    require plugin_dir_path( __FILE__ ).'fields.php';

    // Adicione o código para o esquema de receitas do schema.org abaixo.
    add_action( 'wp_head', 'add_recipe_schema' );
}

function add_recipe_schema() {
    if ( ! is_singular( 'recipe' ) ) {
        return;
    }

    $post_id = get_queried_object_id();

    // Preencha as informações do esquema de receitas usando os campos personalizados ACF.

   $ingredientes = array();
        if( have_rows('ingredientes_da_receita') ){
        while( have_rows('ingredientes_da_receita') ){
        the_row();
        $ingredientes[] = get_sub_field('ingredientes');
        }
    }


    $recipe = array(
        '@context' => 'http://schema.org',
        '@type' => 'Recipe',
        'name' => get_the_title(),
        'description' => get_field( 'descricao', $post_id ),
        'recipeIngredient' => $ingredientes,
        'recipeInstructions' => get_field( 'metodo_de_preparo', $post_id ),
        'totalTime' => get_field( 'recipepress_prepTime', $post_id ),
        'recipeCategory' => get_field( 'categoria', $post_id ),
        'prepTime' => get_field( 'recipepress_totalTime', $post_id ),
        'cookTime' => get_field( 'recipepress_cookTime', $post_id ),
        'recipeDifficulty' => get_field( 'dificuldade', $post_id ),
        'author' => get_field( 'autor_da_receita', $post_id ),
        'keywords' => get_field( 'recipepress_keywords', $post_id ),
        'aggregateRating' => get_field( 'classificacao', $post_id ),
        'recipeCategory' => get_field( 'recipeCuisine', $post_id ),
        'nutrition' => get_field( 'nutricao', $post_id ),
        // Adicione aqui os outros campos do esquema de receitas usando os campos personalizados ACF.
    );



    echo '<script type="application/ld+json">' . wp_json_encode( $recipe ) . '</script>';
}

add_action( 'init', 'create_reciperie_post_type' );
?>
