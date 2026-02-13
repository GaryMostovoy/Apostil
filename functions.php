<?php 

//видалення непотрібних тегів
remove_action('wp_head',             'print_emoji_detection_script', 7 );				
remove_action('admin_print_scripts', 'print_emoji_detection_script' );						
remove_action('wp_print_styles',     'print_emoji_styles' );						
remove_action('admin_print_styles',  'print_emoji_styles' );

remove_action('wp_head', 'wp_resource_hints', 2 ); //remove dns-prefetch
remove_action('wp_head', 'wp_generator'); //remove meta name="generator"
remove_action('wp_head', 'wlwmanifest_link'); //remove wlwmanifest
remove_action('wp_head', 'rsd_link'); // remove EditURI
remove_action('wp_head', 'rest_output_link_wp_head');// remove 'https://api.w.org/
remove_action('wp_head', 'rel_canonical'); //remove canonical
remove_action('wp_head', 'wp_shortlink_wp_head', 10); //remove shortlink
remove_action('wp_head', 'wp_oembed_add_discovery_links'); //remove alternate

// Метатеги
function head_seo_meta_tags(){

}

// відключаємо стилі, які не використовуються і сповільнюють завантаження сторінки
add_action('wp_head', function () {
   //  wp_deregister_style('wp-block-library'); - видаалення
   //  wp_deregister_style('dashicons');
	
	// wp_dequeue_style( 'wp-block-library' ); - видаляє файл з черги на вивід
},1);

// Підключення стилів та скриптів
add_action( 'wp_enqueue_scripts', function () {

    // CSS
    wp_enqueue_style(
        'style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime( get_template_directory() . '/assets/css/style.css' )
    );

    // JS
	wp_enqueue_script(
		'app',  get_template_directory_uri() . '/assets/js/app.js', array(), filemtime( get_template_directory() . '/assets/js/app.js' ), true
	);
	
	wp_localize_script('app', 'wpApiSettings', [
		'nonce' => wp_create_nonce('wp_rest'),
	]);
	
	wp_enqueue_script(
		'api', get_template_directory_uri() . '/assets/js/api.js', array('app'), filemtime( get_template_directory() . '/assets/js/api.js' ), true
	);
});

// Підлючення Calendly
// function enqueue_calendly_script() {
//     wp_enqueue_script('calendly-widget', 'https://assets.calendly.com/assets/external/widget.js', [], null, true);
	
// 	 wp_enqueue_style('calendly-widget-style', 'https://assets.calendly.com/assets/external/widget.css', [], null);
// }
// add_action('wp_enqueue_scripts', 'enqueue_calendly_script');

//Підключення конкретного файлу стилів на конкретну сторінку (можна застосовувати і для скриптів)
function wpse_enqueue_page_template_scripts() {
    if ( is_page_template( 'prices.php' ) ) {
        wp_enqueue_script(
		  'calc-page-prices', get_template_directory_uri() . '/assets/js/calc-page-prices.js', array('app'), filemtime( get_template_directory() . '/assets/js/calc-page-prices.js' ), true
		);
    } else if ( is_page_template( 'order-now.php' ) ) {
		wp_enqueue_script(
			'calc-page-order-now', get_template_directory_uri() . '/assets/js/calc-page-order-now.js', array('app'), filemtime( get_template_directory() . '/assets/js/calc-page-order-now.js' ), true
		);
	} elseif ( is_page_template( 'pay.php' ) ) {
        wp_enqueue_style( 'pay',  get_template_directory_uri() . '/assets/css/pay.css',  [],  filemtime( get_template_directory() . '/assets/css/pay.css' ),  'all'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpse_enqueue_page_template_scripts' );


// Використання номеру телефона як гіперпосилання
add_action( 'wp_head', function() {
	echo '<meta name="format-detection" content="telephone=no">';
});

// Для додавання 
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('custom-logo');

// Автоматичне додовання назви сторінки в тег <title>
add_theme_support( 'title-tag' );

// Додаємо клас до кастомного логотипу
function custom_logo_link_class($html) {
	$html = str_replace('<a', '<a class="header__logo"', $html);
	return $html;
}
add_filter('get_custom_logo', 'custom_logo_link_class');

// Додаємо меню
add_action( 'after_setup_theme', function () {
	register_nav_menus( [
		'menu_header'    => 'Меню header', 
		'menu_footer'    => 'Меню footer', 
		'menu_sitemap'   => 'Меню sitemap', 
	] );
} );


// Додаємо в Меню footer footer__item до li
function add_custom_class_to_menu_items($classes, $item, $args) {
	if ($args->theme_location === 'menu_footer' || $args->theme_location === 'menu_footer_ua') {
		$classes[] = 'footer__item';
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'add_custom_class_to_menu_items', 10, 3);


// Видаляємо вбудований WordPress'ом div в обох меню
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
function my_wp_nav_menu_args( $args='' ){
	$args['container'] = '';
	return $args;
}

// Додавання SVG в список дозволених для завантаження файлів.
add_filter( 'upload_mimes', 'svg_upload_allow' );

function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

//! Виведення даних з форми в адмінку
// Зміна padding в адмінці для CPT
function custom_admin_styles_for_cpt() {
    global $typenow;
    if ($typenow === 'order') {
        echo '<style>
            .widefat td, .widefat th {
                padding: 8px 5px !important;
            }
        </style>';
    }
}
add_action('admin_head', 'custom_admin_styles_for_cpt');

// Створення кастомного типу запису (CTP) Orders
add_action('init', 'register_orders_cpt_fixed', 0);
function register_orders_cpt_fixed() {
    if ( post_type_exists('order') ) return;

    register_post_type('order', array(
        'labels' => array(
            'name'               => 'Orders',
            'singular_name'      => 'Order',
            'menu_name'          => 'Orders',
            'search_items'       => 'Search Orders',
            'not_found'          => 'No orders found',
        ),
        'public'              => true,  
        'show_ui'             => true,  
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-cart',
        'supports'            => array('title'),
        'has_archive'         => false, 
        'rewrite'             => array(
            'slug'       => 'order',  
            'with_front' => false,
        ),
       
        'query_var'           => 'order_query', 
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'capabilities'        => array(
            'create_posts' => 'do_not_allow',
        ),
        'show_in_rest'        => false, 
    ));
}


// Додавання мета-блоків для деталей замовлення
add_action('add_meta_boxes', 'add_order_meta_boxes');
function add_order_meta_boxes() {
    add_meta_box('order_details', 'Order Details', 'render_order_meta_box', 'order', 'normal', 'default');
}
if(isset($_GET["stripe_pay"]))
{
    $a = $_GET["stripe_pay"];
    $link = stripe_payment($a);
    echo $link;
    exit;
}

if(isset($_GET["paypal_pay"]))
{
    $a = $_GET["paypal_pay"];
    $link = create_paypal_order_mail($a);
    echo $link;
    exit;
}

function render_order_meta_box($post) {
    $order_data = get_post_meta($post->ID);
    ?>
	<!-- 	Кнопка відправки листа -->
	<?php 
		$sent = get_post_meta($post->ID, '_order_mail_sent', true);
		$btn_text = $sent ? 'Sent' : 'Send mail';
		$btn_attrs = $sent ? 'disabled aria-disabled="true"' : '';
		$btn_extra_class = $sent ? ' sent' : '';

		echo '<p>';
		echo '<button type="button" class="button button-primary send-mail-btn 123' . esc_attr($btn_extra_class) . '" '
			. 'data-order-id="' . esc_attr($post->ID) . '" ' . $btn_attrs
			. ($sent ? ' style="background:#4CAF50 !important; border-color:#4CAF50 !important; color:#fff !important; cursor:default !important;"' : '')
			. '>' . esc_html($btn_text) . '</button>';
		echo ' <span class="send-mail-status"></span>';
		echo '</p>';
	?>
	<!-- 	 -->
	<p><strong>Order Platform:</strong> <?php echo esc_html($order_data['order_platform'][0] ?? ''); ?></p>
	<p><strong>Order Device:</strong> <?php echo esc_html($order_data['order_device'][0] ?? ''); ?></p>
    <p><strong>Order Number:</strong> <?php echo esc_html($order_data['order_number'][0] ?? ''); ?></p>
    <p><strong>Name:</strong> <?php echo esc_html($order_data['order_name'][0] ?? ''); ?></p>
	<p><strong>Company:</strong> <?php echo esc_html($order_data['order_company'][0] ?? ''); ?></p>
    <p><strong>Phone:</strong> <?php echo esc_html($order_data['order_phone'][0] ?? ''); ?></p>
    <p><strong>Email:</strong> <?php echo esc_html($order_data['order_email'][0] ?? ''); ?></p>
	<p><strong>Adress:</strong> <?php
		$adress = $order_data['order_adress'][0] ?? '';
		$adress = wp_strip_all_tags(str_replace('<br>', ' ', $adress));
		echo esc_html($adress);?>
	</p>
	<p><strong>Return shipping address:</strong> <?php
		$adress = $order_data['order_adress_return'][0] ?? '';
		$adress = wp_strip_all_tags(str_replace('<br>', ' ', $adress));
		echo esc_html($adress);?>
	</p>
    <p><strong>Service Type:</strong><br>
        <?php
		$processingtime = $order_data['order_processing_time'][0] ?? '';
	    if (!empty($processingtime)) {
			echo '' . esc_html($processingtime) . '<br>';
		}

		$documents = get_post_meta($post->ID, 'order_documents', true);
		if (!empty($documents) && !is_array($documents)) {
			$documents = array_map('trim', explode(',', $documents));
		}
		$documents_count = is_array($documents) ? count($documents) : 1;
		echo esc_html($documents_count) . ' documents<br>';
	
		$documents_quantity = $order_data['documents_quantity'][0] ?? '';
		echo esc_html($documents_quantity) . ' quantity documents<br>';
		
        $services = get_post_meta($post->ID, 'order_service', true);
        if (!empty($services)) {
            if (!is_array($services)) {
                $services = array_map('trim', explode(',', $services));
            }
            echo implode('<br>', array_map('esc_html', $services));
        } else {
            echo '-';
        }
        ?>
    </p>
    <p><?php echo esc_html($order_data['order_processing_time'][0] ?? '-'); ?></p>
    <p><strong>Translation:</strong> <?php echo esc_html($order_data['order_translation_pages'][0] ?? ''); ?></p>
    <p><strong>Amount:</strong><?php echo '$' . esc_html($order_data['order_amount'][0] ?? ''); ?></p>
    <p>
        <label><strong>Tracking USPS:</strong></label><br>
        <input type="text" name="order_tracking" value="<?php echo esc_attr($order_data['order_tracking'][0] ?? ''); ?>" style="width:100%;">
    </p>
    <p>
        <label><strong>Tracking DHL:</strong></label><br>
        <input type="text" name="order_tracking_dhl" value="<?php echo esc_attr($order_data['order_tracking_dhl'][0] ?? ''); ?>" style="width:100%;">
    </p>
	<p><strong>Return shipping phone number:</strong> <?php echo esc_html($order_data['order_phone_return'][0] ?? ''); ?></p>
	<?php
		if (empty($order_status)) {
			$order_status = 'Order Received';
		}
	?>
    <p>
        <label><strong>Status:</strong></label><br>
		<?php
			$order_status = get_post_meta($post->ID, 'order_status', true);
			if (empty($order_status)) {
				$order_status = 'Order Received';
			}
		?>
        <select name="order_status" style="width:100%;">
            <option value="">Select</option>

            <option value="Order Received" <?php selected($order_status, 'Order Received'); ?>>
                Order Received
            </option>

            <option value="Under Review" <?php selected($order_status, 'Under Review'); ?>>
                Under Review
            </option>

            <option value="Apostille in Progress" <?php selected($order_status, 'Apostille in Progress'); ?>>
                Apostille in Progress
            </option>

            <option value="Shipped via USPS" <?php selected($order_status, 'Shipped via USPS'); ?>>
                Shipped via USPS
            </option>

            <option value="Shipped via DHL" <?php selected($order_status, 'Shipped via DHL'); ?>>
                Shipped via DHL
            </option>

            <option value="Delivered" <?php selected($order_status, 'Delivered'); ?>>
                Delivered
            </option>

            <option value="Cancelled" <?php selected($order_status, 'Cancelled'); ?>>
                Cancelled
            </option>
        </select>
    </p>
    <?php
}

// Збереження дефолтного статсусу замовлення в БД
add_action('save_post_order', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    $status = get_post_meta($post_id, 'order_status', true);

    if (empty($status)) {
        update_post_meta($post_id, 'order_status', 'Order Received');
    }
});

add_action('admin_head', 'custom_order_column_width');
function custom_order_column_width() {
    global $pagenow;
    if ($pagenow != 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] != 'order') return;
    echo '<style>
        .wp-list-table .column-order_number { width: 90px; }
		.wp-list-table .column-order_name { width: 160px; }
		.wp-list-table .column-order_adress { width: 200px }
		.wp-list-table .column-order_service { width: 80px; }
		.wp-list-table .column-order_amount { width: 60px; }
        .wp-list-table .column-order_status { width: 120px; text-align: center; }
        .wp-list-table .column-order_tracking { width: 160px; }
    </style>';
}

// Колонки в адмінці
add_filter('manage_order_posts_columns', 'set_custom_order_columns');
function set_custom_order_columns($columns) {
    unset($columns['date']);
    return array(
        'cb' => '<input type="checkbox" />',
        'order_number' => 'Order',
        'order_name' => 'Name',
		'order_adress' => 'Address',
        'order_service' => 'Service',
        'order_amount' => 'Amount',
		'order_status' => 'Status',
        'order_tracking' => 'Tracking',
    );
}

add_action('manage_order_posts_custom_column', 'custom_order_column', 10, 2);
function custom_order_column($column, $post_id) {
    switch ($column) {
        case 'order_date':
            echo esc_html(get_post_meta($post_id, 'order_date', true));
            break;
        case 'order_number':
            echo esc_html(get_post_meta($post_id, 'order_number', true));
            break;
        case 'order_name':
            $name = esc_html(get_post_meta($post_id, 'order_name', true));
			$company = esc_html(get_post_meta($post_id, 'order_company', true));
            $phone = esc_html(get_post_meta($post_id, 'order_phone', true));
            $email = esc_html(get_post_meta($post_id, 'order_email', true));
            echo "<strong>{$name}</strong><br>";
			if ($company) echo "{$company}<br>";
            if ($phone) echo "<span style='color:#555;'>📞 {$phone}</span><br>";
            if ($email) echo "<a href='mailto:{$email}'>{$email}</a>";
            break;
		case 'order_adress':
			echo wp_kses_post(get_post_meta($post_id, 'order_adress', true)) . '<br>' . '<br>';
			echo wp_kses_post(get_post_meta($post_id, 'order_adress_return', true));
			break;
        case 'order_service':
			$processingtime = get_post_meta($post_id, 'order_processing_time', true);
			if (!empty($processingtime)) {
				$first_word = explode(' ', trim($processingtime))[0]; // перше слово
				echo esc_html($first_word) . '<br>';
			}
			
			$documents = get_post_meta($post_id, 'order_documents', true);
			if (!empty($documents) && !is_array($documents)) {
				$documents = array_map('trim', explode(',', $documents));
			}
			$documents_count = is_array($documents) ? count($documents) : 1;
			echo esc_html($documents_count) . ' documents<br>';
			
			$documents_quantity = get_post_meta($post_id, 'documents_quantity', true);			
			echo esc_html($documents_quantity) . ' Qty of Docs<br>';

			$documents_translation = get_post_meta($post_id, 'order_translation_pages', true);
			echo esc_html($documents_translation) . ' Translation<br>';

			$processingtime = esc_html(get_post_meta($post_id, 'order_processing_time', true));
            $services = get_post_meta($post_id, 'order_service', true);
            if (!is_array($services)) {
                $services = array_map('trim', explode(',', $services));
            }
            echo implode('<br>', array_map('esc_html', $services));
            break;
        case 'order_amount':
            echo '$' . esc_html(get_post_meta($post_id, 'order_amount', true));
            break;
        case 'order_tracking':
            $tracking = trim(get_post_meta($post_id, 'order_tracking', true));
			$tracking_dhl = trim(get_post_meta($post_id, 'order_tracking_dhl', true));
            if (!empty($tracking)) {
                $link = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . urlencode($tracking);
                echo '<a href="' . esc_url($link) . '" target="_blank">' . esc_html($tracking) . '</a>';
            } if (!empty($tracking_dhl)) {
                $link = 'https://mydhl.express.dhl/us/en/tracking.html#/results?id=' . urlencode($tracking_dhl);
                echo '<a href="' . esc_url($link) . '" target="_blank">' . esc_html($tracking_dhl) . '</a>';
            } 
			if (empty($tracking) && empty($tracking_dhl)) {
				echo '-';
			}
            break;           
		case 'order_status':
			$status = get_post_meta($post_id, 'order_status', true);
			if (empty($status)) {
				$status = 'Order Received'; // Значення за замовчуванням
			}
			echo '<span data-status="' . esc_attr($status) . '">' . esc_html($status) . '</span>';
			break;
    }
}

	// Збереження значень полів Tracking і Status
	add_action('save_post_order', 'save_order_meta_fields');
	function save_order_meta_fields($post_id) {
		// Перевірка на автозбереження
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		// Перевірка прав доступа
		if (!current_user_can('edit_post', $post_id)) return;

		// Перевірка типу запису
		if (get_post_type($post_id) !== 'order') return;

		// Зберігаємо Tracking
		if (isset($_POST['order_tracking'])) {
			update_post_meta($post_id, 'order_tracking', sanitize_text_field($_POST['order_tracking']));
		}
		if (isset($_POST['order_tracking_dhl'])) {
			update_post_meta($post_id, 'order_tracking_dhl', sanitize_text_field($_POST['order_tracking_dhl']));
		}

		// Зберігаємо Status
		if (isset($_POST['order_status']) && $_POST['order_status'] !== '') {
			update_post_meta(
				$post_id,
				'order_status',
				sanitize_text_field($_POST['order_status'])
			);
		} else {
			// Якщо нічого не передали — ставимо дефолт
			update_post_meta($post_id, 'order_status', 'Order Received');
		}
	}


// Стилі для назв колонок на мобільних пристроях
function my_admin_fix_styles() {
    echo '<style>
        @media screen and (max-width: 782px) {
            td.order_name::before,
			td.order_adress::before,
			td.order_service::before,
			td.order_amount::before,
			td.order_status::before,
			td.order_tracking::before {
				position:relative !important;
				left:0 !important;
				text-align:left !important;
            }
			
			.wp-list-table .column-order_status {
				text-align:left !important;
			}
        }
    </style>';
}
add_action("admin_head", "my_admin_fix_styles");


add_action('admin_footer', function() {
?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ТІЛЬКИ МОБІЛЬНИЙ WP ( <= 782px)
  if (window.innerWidth > 782) return;

  document.querySelectorAll('tr').forEach(row => {

    const orderTd = row.querySelector('.column-order_number')
    const nameTd  = row.querySelector('.column-order_name strong')
	const orderStatusTd  = row.querySelector('.column-order_status span')	

    if (!orderTd || !nameTd) return;

    if (orderTd.querySelector('.order-inline-name')) return;

    const name = nameTd.textContent.trim();

    const wrap = document.createElement('div');
	const content = document.createElement('div');
    wrap.className = 'order-mobile-wrap';
	  content.className = 'order-mobile-content';

    const number = document.createElement('span');
    number.className = 'order-mobile-number';
    number.innerHTML = orderTd.childNodes[0].textContent.trim();

    const person = document.createElement('strong');
    person.className = 'order-inline-name';
    person.textContent = name;

	content.append(person, orderStatusTd);
	wrap.append(number, content);
 
    orderTd.childNodes[0].textContent = '';

  	wrap.prepend(content);
    orderTd.prepend(wrap);

  })

});
</script>
<style>
@media (max-width: 782px) {

  .order-mobile-wrap {
	  display: flex;
	  flex-direction:row-reverse;
	  gap:40px;
	  align-items: flex-start;
	  justify-content: flex-end;
	  width: 100%;
  }
	.order-mobile-content {
		display:flex;
		flex-direction:column;
		align-items:flex-start
	}
}
</style>
<?php
});


// Зміна стилів кнопки для відправки листа вдячності
add_action('admin_head', 'send_mail_button_admin_styles');
function send_mail_button_admin_styles() {
    $screen = get_current_screen();
    if (! $screen || $screen->post_type !== 'order') return;
    ?>
    <style>
    /* Якщо є клас .sent — перебиваємо стилі */
    .send-mail-btn.sent {
        background: #4CAF50 !important;
        border-color: #4CAF50 !important;
        color: #fff !important;
        box-shadow: none !important;
        text-shadow: none !important;
        cursor: default !important;
    }
    .send-mail-btn.sent[disabled],
    .send-mail-btn.sent:disabled {
        background: #4CAF50 !important;
        border-color: #4CAF50 !important;
        color: #fff !important;
        cursor: default !important;
    }
    </style>
    <?php
}

// JS-обробник для AJAX-відправки листа вдячності (з кнопки Send mail в СРТ)
add_action('admin_footer', 'add_send_mail_script');
function add_send_mail_script() {
    $screen = get_current_screen();
    if ($screen->post_type !== 'order') return;
    ?>
    <script>
    jQuery(document).ready(function($){
        $('.button.button-primary.send-mail-btn').on('click', function(){
            var $btn = $(this);
            var orderId = $btn.data('order-id');
            $btn.prop('disabled', true).text('Sending...');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'send_order_mail',
                    order_id: orderId,
                    _ajax_nonce: '<?php echo wp_create_nonce("send_order_mail_nonce"); ?>'
                },
                success: function(response){
                    if(response.success){
                        $btn.addClass('sent')
							.css({'background':'#4CAF50','border-color':'#4CAF50','color':'#fff'})
                            .text('Sent')
							.prop('disabled', true);
                    } else {
                        alert('Error: ' + response.data);
                        $btn.prop('disabled', false).text('Send mail');
                    }
                },
                error: function(){
                    alert('Server error.');
                    $btn.prop('disabled', false).text('Send mail');
                }
            });
        });
    });
     </script>
     <?php
}

// PHP-обробник (AJAX) відправки листа вдячності
if (isset($_POST['action']) && $_POST['action'] === 'send_order_mail') {
    $phpmailer_path = get_template_directory() . '/assets/files/sendmail/phpmailer/';
    require_once $phpmailer_path . 'src/Exception.php';
    require_once $phpmailer_path . 'src/PHPMailer.php';
    require_once $phpmailer_path . 'src/SMTP.php';

    class_alias('PHPMailer\PHPMailer\PHPMailer', 'PHPMailer');
    class_alias('PHPMailer\PHPMailer\Exception', 'PHPMailerException');
}

add_action('wp_ajax_send_order_mail', 'send_order_mail_callback');
function send_order_mail_callback() {
    check_ajax_referer('send_order_mail_nonce');

    $order_id = intval($_POST['order_id'] ?? 0);
    if (!$order_id) wp_send_json_error('Invalid order ID.');

    $email = get_post_meta($order_id, 'order_email', true);
    $name  = get_post_meta($order_id, 'order_name', true);
    if (empty($email)) wp_send_json_error('No email specified.');

    $templatePath = get_template_directory() . '/assets/files/sendmail/email-template-thanks.html';
    if (!file_exists($templatePath)) wp_send_json_error('Template not found.');
    $template = file_get_contents($templatePath);
    $template = str_replace('{{name-1}}', esc_html($name), $template);

    try {
        $mail = new PHPMailer(true); 
		
        $mail->isSMTP();
        $mail->Host = 'mail.apostil.co';
        $mail->SMTPAuth = true;
        $mail->Username = 'order@apostil.co';
		$mail->Password   = 'sodmI7-ryvhys-xyzpib';
		$mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('order@apostil.co', 'Apostil Inc');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for your order';
        $mail->Body    = $template;

        $mail->send();
		
		// зберігаємо, щоб кнопка залишалася недоступною після перезавантаження сторінки
		update_post_meta($order_id, '_order_mail_sent', 1);
		
		wp_send_json_success('Mail sent to ' . $email);		
    } catch (PHPMailerException $e) {
        wp_send_json_error('Mail failed: ' . $e->getMessage());
    }
}

// Приховати блок Yoast SEO в редакторі Gutenberg для CPT 'order'
add_action('admin_head', function () {
	global $post;

	if (!is_admin() || !isset($post)) return;

	if ($post->post_type === 'Order') {
		echo '<style>
			.components-panel__body[aria-label="Yoast SEO"],
			.wpseo-metabox-root,
			#yoast-seo-meta-section {
				display: none !important;
			}
		</style>';
	}
});

// Вимкнути блок Yoast в robots.txt
add_filter('wpseo_robots_txt', '__return_false', 999);

// Відключити аналіз SEO и читабельності
add_filter('wpseo_use_page_analysis', function($use, $post = null) {
	if ($post && isset($post->post_type) && $post->post_type === 'Order') {
		return false;
	}
	return $use;
}, 10, 2);

// Відключити аналіз посилань
add_filter('wpseo_link_count_enable', function($enabled) {
	global $post;
	if ($post && $post->post_type === 'order') {
		return false;
	}
	return $enabled;
});

// Відключити стандартний Status WP в СРТ Order
add_action('admin_head-edit.php', function () {
    $screen = get_current_screen();
    if ($screen->post_type === 'order') {
        echo '<style>
            .inline-edit-col .inline-edit-group.wp-clearfix { display: none; }
        </style>';
    }
});


// Прибираємо View і Quick Edit з СРТ
add_filter('post_row_actions', 'remove_order_row_actions', 10, 2);
function remove_order_row_actions($actions, $post) {
    if ($post->post_type !== 'order') return $actions;

    // Видаляємо View
    if (isset($actions['view'])) {
        unset($actions['view']);
    }

    // Видаляємо Quick Edit
    if (isset($actions['inline hide-if-no-js'])) {
        unset($actions['inline hide-if-no-js']);
    }

    return $actions;
}

// Додавання noindex, nofollow для сторінок замовлення
add_action('wp_head', function () {
    if (!isset($_SERVER['REQUEST_URI'])) return;

    $uri = $_SERVER['REQUEST_URI'];

    // Для сторінок виду /order-123456 или /order-0628-763671
    if (preg_match('#^/order-[0-9\-]+/?$#', $uri)) {
        echo "<meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }

    // Для сторінок виду /order/123456
    elseif (preg_match('#^/order/[0-9]+/?$#', $uri)) {
        echo "<meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }
});

if(isset($_GET['get-stripe']))
{
    $amountStripe = $_GET['get-stripe'];
    $link = stripe_payment($amountStripe);
    echo $link;
    exit;
}

// paypal
if(isset($_POST["order-now"]))
{
    $clientId = 'AQK6hmUZQO-efOJqKN9MDpzoPa_3dHtPuBHn--L2Lx5JDuUT3i29ktIr2UyAlXznyzXCs_iyzDKlU0PQ';
    $secret = 'ELqkyJbZRqBasf9Wa5-LtDAGJc7kmIq9ULqUYHR7SNV85gL8R2eZXD-Egxthsi3HuvCEopfxkKcsYdGS';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Accept-Language: en_US"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $accessToken = $data['access_token'];

    if(isset($_GET["check_pay"]))
    {
        $orderId = $_GET["check_pay"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/checkout/orders/$orderId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        echo json_encode(['status' => $response['status'] ?? 'UNKNOWN']);
        exit;
    }

    $amountPay = $_POST['paypal_amount'];
    
    if(!preg_match('/^\d+(\.\d+)?$/', $amountPay))
    {
        $resp = [
            'success' => false,
            'message' => 'Invalid amount'
        ];
        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
    
    $amountPay = number_format($amountPay, 2, '.', '');

    $paymentData = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'amount' => [
                'currency_code' => 'USD',
                'value' => $amountPay
            ]
        ]],
        'application_context' => [
            'return_url' => 'https://apostil.co/paypal_return.php',
            'cancel_url' => 'https://apostil.co/paypal_return.php'
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $order = json_decode($response, true);

    $approveLink = '';
    foreach ($order['links'] as $link) {
        if ($link['rel'] === 'approve') {
            $approveLink = $link['href'];
            break;
        }
    }
	    
    $resp = [
        'success' => true,
        'order_id' => $order['id'],
        'url' => $approveLink
    ];

    header('Content-Type: application/json');

    echo json_encode($resp);
    exit;
}

// Редірект зі сторінок з ВЕЛИКОГО регістра на маленький
add_action('template_redirect', function() {
    $uri = $_SERVER['REQUEST_URI'];
    if (preg_match('/[A-Z]/', $uri)) {
        $lower = strtolower($uri);
        if ($uri !== $lower) {
            wp_redirect(home_url($lower), 301);
            exit;
        }
    }
});

// Відключаємо wp-block-library
function disable_gutenberg_styles() {
    wp_dequeue_style('wp-block-library');
    wp_deregister_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_deregister_style('wp-block-library-theme');
    wp_dequeue_style('global-styles'); 
    wp_deregister_style('global-styles');
}
add_action('wp_enqueue_scripts', 'disable_gutenberg_styles', 999);

// Був конфлікт СРТ з виведенням pages
add_action('pre_get_posts', function($query) {
    if (!is_admin()) return;

    // Перевіряємо лише сторінки
    if ($query->get('post_type') === 'page') {
        // Всі фільтри, які впливають на запит
        error_log('--- WP Admin Pages Query ---');
        error_log('Query vars: ' . print_r($query->query_vars, true));
        $filters = has_filter('pre_get_posts');
        error_log('Filters on pre_get_posts: ' . ($filters ? 'YES' : 'NO'));
    }
}, 0);

// Додаємо префікс /blog/ до постів
add_filter('post_link', function($permalink, $post) {
    if ($post->post_type === 'post') {
        $category = get_the_category($post->ID);
        if (!empty($category)) {
            $cat_slug = $category[0]->slug; // Берем первую категорию
        } else {
            $cat_slug = 'uncategorized';
        }
        return home_url('/blog/' . $cat_slug . '/' . $post->post_name . '/');
    }
    return $permalink;
}, 10, 2);

// Щоб листи з додатку через АРІ надсилалися з order@apostil.co
add_filter('wp_mail_from', function () {
    return 'order@apostil.co';
});

add_filter('wp_mail_from_name', function () {
    return 'Apostil Inc';
});

// Редирект з /support/ на /about-us/
add_action('init', function () {
    if ($_SERVER['REQUEST_URI'] === '/support/') {
        wp_redirect(home_url('/about-us/'), 301);
        exit;
    }
});


// --------------------------------- API -------------------------------------- //
// config (select'ы, тексты, списки)
add_action('wp_ajax_get_calculator_config', 'get_calculator_config');
add_action('wp_ajax_nopriv_get_calculator_config', 'get_calculator_config');

function get_calculator_config() {

    $response = wp_remote_get(
        home_url('/wp-json/calculator/v1/config'),
        [
            'headers' => [
                'X-App-Key' => APP_API_KEY
            ],
            'timeout' => 10
        ]
    );

    if (is_wp_error($response)) {
        wp_send_json_error('API error', 500);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    wp_send_json_success($data);
}

// ПОСЛУГИ І ВАРТІСТЬ 
add_action('wp_ajax_get_prices_items', 'get_prices_items');
add_action('wp_ajax_nopriv_get_prices_items', 'get_prices_items');

function get_prices_items() {

    $response = wp_remote_get(
        home_url('/wp-json/prices/v1/items'),
        [
            'headers' => [
                'X-App-Key' => APP_API_KEY
            ],
            'timeout' => 10
        ]
    );

    if (is_wp_error($response)) {
        wp_send_json_error('API error', 500);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    wp_send_json_success($data);
}


// calculation
add_action('wp_ajax_calculate_price', 'calculate_price');
add_action('wp_ajax_nopriv_calculate_price', 'calculate_price');

function calculate_price() {

	$payload = json_decode(file_get_contents('php://input'), true);

	if (empty($payload)) {
		wp_send_json_error('Empty payload', 400);
	}

	$response = wp_remote_post(
		home_url('/wp-json/calculator/v1/prices/calculation'),
		[
			'headers' => [
				'Content-Type' => 'application/json',
				'X-App-Key'    => APP_API_KEY,
			],
			'body'    => wp_json_encode($payload),
			'timeout' => 10,
		]
	);

	if (is_wp_error($response)) {
		wp_send_json_error('API error', 500);
	}

	$data = json_decode(wp_remote_retrieve_body($response), true);
	wp_send_json_success($data);
}


// calculation-order
add_action('wp_ajax_calculate_price_order', 'calculate_price_order');
add_action('wp_ajax_nopriv_calculate_price_order', 'calculate_price_order');

function calculate_price_order() {

	$payload = json_decode(file_get_contents('php://input'), true);

	if (empty($payload)) {
		wp_send_json_error('Empty payload', 400);
	}

	$response = wp_remote_post(
		home_url('/wp-json/calculator/v1/prices/calculation_order'),
		[
			'headers' => [
				'Content-Type' => 'application/json',
				'X-App-Key'    => APP_API_KEY,
			],
			'body'    => wp_json_encode($payload),
			'timeout' => 10,
		]
	);

	if (is_wp_error($response)) {
		wp_send_json_error('API error', 500);
	}

	$data = json_decode(wp_remote_retrieve_body($response), true);
	wp_send_json_success($data);
}

// ДЛЯ ОПЛАТИ НА СТОРІНЦІ /РАУ
add_action('wp_ajax_get_order_for_payment', 'get_order_for_payment');
add_action('wp_ajax_nopriv_get_order_for_payment', 'get_order_for_payment');

function get_order_for_payment() {

	$order = $_GET['order'] ?? '';

	if (empty($order)) {
		wp_send_json_error('Order number is empty', 400);
	}

	$response = wp_remote_get(
		add_query_arg(
			['order' => $order],
			home_url('/wp-json/orders/v1/get')
		),
		[
			'headers' => [
				'X-App-Key' => APP_API_KEY,
			],
			'timeout' => 10,
		]
	);

	if (is_wp_error($response)) {
		wp_send_json_error('API request failed', 500);
	}

	$body = json_decode(wp_remote_retrieve_body($response), true);

	if (empty($body['success'])) {
		wp_send_json_error('Order not found', 404);
	}

	wp_send_json_success($body['order']);
}
