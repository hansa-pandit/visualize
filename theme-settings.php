<?php
/**
 * @file
 * theme-settings.php
 *
 * Provides theme settings for Bootstrap Barrio based themes when admin theme is not.
 *
 * @see ./includes/settings.inc
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function visualize_form_system_theme_settings_alter(&$form, FormStateInterface $form_state, $form_id = NULL) {

    // General "alters" use a form id. Settings should not be set here. The only
    // thing useful about this is if you need to alter the form for the running
    // theme and *not* the theme setting.
    // @see http://drupal.org/node/943212
    if (isset($form_id)) {
        return;
    }

    //Change collapsible fieldsets (now details) to default #open => FALSE.
    $form['theme_settings']['#open'] = FALSE;
    $form['logo']['#open'] = FALSE;
    $form['favicon']['#open'] = FALSE;

    // Library settings
    if (\Drupal::moduleHandler()->moduleExists('bootstrap_library')) {
        $form['bootstrap_barrio_library'] = array(
            '#type' => 'select',
            '#title' => t('Load library'),
            '#description' => t('Select how to load the Bootstrap Library.'),
            '#default_value' => theme_get_setting('bootstrap_barrio_library'),
            '#options' => array(
                'cdn' => t('CDN'),
                'development' => t('Local non minimized (development)'),
                'production' => t('Local minimized (production)'),
            ),
            '#empty_option' => t('None'),
            '#description' => t('If none is selected you should load the library via Bootstrap Library or manually. If CDN is selected, the library version must be configured on @boostrap_library_link',  array('@bootstrap_library_link' => Drupal::l('Bootstrap Library Settings' , Url::fromRoute('bootstrap_library.admin')))),
        );
    }

    // Vertical tabs
    $form['bootstrap'] = array(
        '#type' => 'vertical_tabs',
        '#prefix' => '<h2><small>' . t('Visualize theme Settings') . '</small></h2>',
        '#weight' => -10,
    );

    // Layout.
    $form['layout'] = array(
        '#type' => 'details',
        '#title' => t('Layout'),
        '#group' => 'bootstrap',
    );

    //Container
    $form['layout']['container'] = array(
        '#type' => 'details',
        '#title' => t('Container'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    $form['layout']['container']['bootstrap_barrio_fluid_container'] = array(
        '#type' => 'checkbox',
        '#title' => t('Fluid container'),
        '#default_value' => theme_get_setting('bootstrap_barrio_fluid_container'),
        '#description' => t('Use <code>.container-fluid</code> class. See : @bootstrap_barrio_link', array(
            '@bootstrap_barrio_link' => Drupal::l('Fluid container' , Url::fromUri('http://getbootstrap.com/css/' , ['absolute' => TRUE , 'fragment' => 'grid-example-fluid'])),
        )),
    );

    // List of regions
    $theme = \Drupal::theme()->getActiveTheme()->getName();
    $region_list = system_region_list($theme, $show = REGIONS_ALL);
    // Only for initial setup if not defined on install
    $nowrap = [
        'breadcrumb',
        'highlighted',
        'content',
        'primary_menu',
        'header',
        'sidebar_first',
        'sidebar_second',
    ];

    //Region
    $form['layout']['region'] = array(
        '#type' => 'details',
        '#title' => t('Region'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    foreach ($region_list as $name => $description) {
        if ( theme_get_setting(' visualize_region_clean_' . $name) !== NULL) {
            $region_clean = theme_get_setting('bootstrap_barrio_region_clean_' . $name);
        }
        else {
            $region_clean = in_array($name, $nowrap);
        }
        if ( theme_get_setting('visualize_region_class_' . $name) !== NULL) {
            $region_class = theme_get_setting('bootstrap_barrio_region_class_' . $name);
        }
        else {
            $region_class = $region_clean ? NULL : 'row';
        }

        $form['layout']['region'][$name] = array(
            '#type' => 'details',
            '#title' => $description,
            '#collapsible' => TRUE,
            '#collapsed' => TRUE,
        );
        $form['layout']['region'][$name]['visualize_region_clean_' . $name] = array(
            '#type' => 'checkbox',
            '#title' => t('Clean wrapper for @description region', array('@description' => $description)),
            '#default_value' => $region_clean,
        );
        $form['layout']['region'][$name]['visualize_region_class_' . $name] = array(
            '#type' => 'textfield',
            '#title' => t('Classes for @description region', array('@description' => $description)),
            '#default_value' => $region_class,
            '#size' => 40,
            '#maxlength' => 40,
        );
    }

    // Sidebar Position
    $form['layout']['sidebar_position'] = array(
        '#type' => 'details',
        '#title' => t('Sidebar Position'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    $form['layout']['sidebar_position']['visualize_sidebar_position'] = array(
        '#type' => 'select',
        '#title' => t('Sidebars Position'),
        '#default_value' => theme_get_setting('bootstrap_barrio_sidebar_position'),
        '#options' => array(
            'left' => t('Left'),
            'both' => t('Both sides'),
            'right' => t('Right'),
        ),
    );
    $form['layout']['sidebar_position']['visualize_content_offset'] = array(
        '#type' => 'select',
        '#title' => t('Content Offset'),
        '#default_value' => theme_get_setting('visualize_content_offset'),
        '#options' => array(
            0 => t('None'),
            1 => t('1 Cols'),
            2 => t('2 Cols'),
        ),
    );

    // Sidebar First
    $form['layout']['sidebar_first'] = array(
        '#type' => 'details',
        '#title' => t('Sidebar First Layout'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    $form['layout']['sidebar_first']['visualize_sidebar_collapse'] = array(
        '#type' => 'checkbox',
        '#title' => t('Sidebar collapse'),
        '#default_value' => theme_get_setting('visualize_sidebar_collapse'),
    );
    $form['layout']['sidebar_first']['bootstrap_barrio_sidebar_first_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar First Width'),
        '#default_value' => theme_get_setting('visualize_sidebar_first_width'),
        '#options' => array(
            2 => t('2 Cols'),
            3 => t('3 Cols'),
            4 => t('4 Cols'),
        ),
    );
    $form['layout']['sidebar_first']['visualize_sidebar_first_offset'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar First Offset'),
        '#default_value' => theme_get_setting('visualize_sidebar_first_offset'),
        '#options' => array(
            0 => t('None'),
            1 => t('1 Cols'),
            2 => t('2 Cols'),
        ),
    );

    // Sidebar Second
    $form['layout']['sidebar_second'] = array(
        '#type' => 'details',
        '#title' => t('Sidebar Second Layout'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
    );
    $form['layout']['sidebar_second']['visualize_sidebar_second_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar Second Width'),
        '#default_value' => theme_get_setting('visualize_sidebar_second_width'),
        '#options' => array(
            2 => t('2 Cols'),
            3 => t('3 Cols'),
            4 => t('4 Cols'),
        ),
    );
    $form['layout']['sidebar_second']['visualize_sidebar_second_offset'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar Second Offset'),
        '#default_value' => theme_get_setting('visualize_sidebar_second_offset'),
        '#options' => array(
            0 => t('None'),
            1 => t('1 Cols'),
            2 => t('2 Cols'),
        ),
    );

    // Fonts.
    // General.
    $form['fonts'] = array(
        '#type' => 'details',
        '#title' => t('Fonts'),
        '#group' => 'bootstrap',
    );

    $form['fonts']['fonts'] = array(
        '#type' => 'details',
        '#title' => t('Fonts'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );
    $form['fonts']['fonts']['visualize_google_fonts'] = array(
        '#type' => 'select',
        '#title' => t('Google Fonts Combination'),
        '#default_value' => theme_get_setting('bootstrap_barrio_google_fonts'),
        '#empty_option' => t('None'),
        '#options' => array(
            'roboto' => 'Roboto Condensed, Roboto',
            'monserrat_lato' => 'Monserrat, Lato',
            'alegreya_roboto' => 'Alegreya, Roboto Condensed, Roboto',
            'dancing_garamond' => 'Dancing Script, EB Garamond',
            'amatic_josefin' => 'Amatic SC, Josefin Sans',
            'oswald_droid' => 'Oswald, Droid Serif',
            'playfair_alice' => 'Playfair Display, Alice',
            'dosis_opensans' => 'Dosis, Open Sans',
            'lato_hotel' => 'Lato, Grand Hotel',
            'medula_abel' => 'Medula One, Abel',
            'fjalla_cantarell' => 'Fjalla One, Cantarell',
            'coustard_leckerli' => 'Coustard Ultra, Leckerli One',
            'philosopher_muli' => ' Philosopher, Muli ',
            'vollkorn_exo' => 'Vollkorn, Exo',
        ),
    );


    // General.
    $form['colors'] = array(
        '#type' => 'details',
        '#title' => t('Colors'),
        '#group' => 'bootstrap',
    );

    // Alerts.
    $form['colors']['alerts'] = array(
        '#type' => 'details',
        '#title' => t('Colors'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );
    $form['colors']['alerts']['visualize_system_messages'] = array(
        '#type' => 'select',
        '#title' => t('System Messages Color Scheme'),
        '#default_value' => theme_get_setting('visualize_system_messages'),
        '#empty_option' => t('Default'),
        '#options' => array(
            'messages_light' => t('Light'),
            'messages_dark' => t('Dark'),
        ),
        '#description' => t('Replace standard color scheme for the system mantainance alerts with Google Material Design color scheme'),
    );
    $form['colors']['tables'] = array(
        '#type' => 'details',
        '#title' => t('Tables'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );
    $form['colors']['tables']['visualize_table_style'] = array(
        '#type' => 'select',
        '#title' => t('Table cell style'),
        '#default_value' => theme_get_setting('visualize_table_style'),
        '#empty_option' => t('Default'),
        '#options' => array(
            'table-striped' => t('Striped'),
            'table-bordered' => t('Bordered'),
        ),
    );
    $form['colors']['tables']['visualize_table_hover'] = array(
        '#type' => 'checkbox',
        '#title' => t('Hover efect over table cells'),
        '#description' => t('Apply Bootstrap table hover effect.'),
        '#default_value' => theme_get_setting('visualize_table_hover'),
    );
    $form['colors']['tables']['bootstrap_barrio_table_head'] = array(
        '#type' => 'select',
        '#title' => t('Table Header Color Scheme'),
        '#default_value' => theme_get_setting('visualize_table_head'),
        '#empty_option' => t('Default'),
        '#options' => array(
            'thead-light' => t('Light'),
            'thead-dark' => t('Dark'),
        ),
        '#description' => t('Select the table head color scheme'),
    );
}
