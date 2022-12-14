<?php

namespace Binarithm\Superstore\RESTAPI;

use WC_Product_Attribute;
use WC_Product_Download;
use WC_Product_Factory;
use WC_Product_Simple;
use WC_REST_Exception;
use WP_Error;
use WP_REST_Server;
use Binarithm\Superstore\Abstracts\AbstractRestPostsController;

/**
 * Product REST API Controller
 */
class Product extends AbstractRestPostsController {

	/**
	 * Endpoint namespace
	 *
	 * @var string
	 */
	protected $namespace = 'superstore/v1';

	/**
	 * Route name
	 *
	 * @var string
	 */
	protected $base = 'products';

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type = 'product';

	/**
	 * Post status
	 *
	 * @var array
	 */
	protected $post_status = array( 'publish', 'pending', 'draft' );

	/**
	 * Register all product routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->base,
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'args'                => $this->get_collection_params(),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/(?P<id>[\d]+)/',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'args'                => $this->get_collection_params(),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'check_permission' ),
					'args'                => array(
						'force' => array(
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'superstore' ),
						),
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/downloadable',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_downloadable_products' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/best-selling',
			array(
				'args' => array(
					'per_page'  => array(
						'description' => __( 'Number of products', 'superstore' ),
						'type'        => 'integer',
						'default'     => 6,
					),
					'page'      => array(
						'description' => __( 'Number of page', 'superstore' ),
						'type'        => 'integer',
						'default'     => 1,
					),
					'seller_id' => array(
						'description' => __( 'Seller ID', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_best_selling_products' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/featured',
			array(
				'args' => array(
					'per_page'  => array(
						'description' => __( 'Number of products', 'superstore' ),
						'type'        => 'integer',
						'default'     => 6,
					),
					'page'      => array(
						'description' => __( 'Number of page', 'superstore' ),
						'type'        => 'integer',
						'default'     => 1,
					),
					'seller_id' => array(
						'description' => __( 'Seller ID', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_featured_products' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/top-rated',
			array(
				'args' => array(
					'per_page'  => array(
						'description' => __( 'Number of products', 'superstore' ),
						'type'        => 'integer',
						'default'     => 6,
					),
					'page'      => array(
						'description' => __( 'Number of page', 'superstore' ),
						'type'        => 'integer',
						'default'     => 1,
					),
					'seller_id' => array(
						'description' => __( 'Seller ID', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_rated_products' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->base . '/latest',
			array(
				'args' => array(
					'per_page'  => array(
						'description' => __( 'Number of products', 'superstore' ),
						'type'        => 'integer',
						'default'     => 6,
					),
					'page'      => array(
						'description' => __( 'Number of page', 'superstore' ),
						'type'        => 'integer',
						'default'     => 1,
					),
					'seller_id' => array(
						'description' => __( 'Seller ID', 'superstore' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_latest_products' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	/**
	 * Check user is seller and managing only own data
	 *
	 * @param obj|array $request Request.
	 * @return bool
	 */
	public function check_permission( $request ) {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			return true;
		}

		if ( superstore_is_user_seller( get_current_user_id() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get object
	 *
	 * @param string|int $id Product id.
	 * @return object
	 */
	public function get_object( $id ) {
		return wc_get_product( $id );
	}

	/**
	 * Get best selling products
	 *
	 * @param WP_Rest_Request $request Rest request.
	 * @return array
	 */
	public function get_best_selling_products( $request ) {
		$products = array();
		$products = superstore()->product->get_best_selling_products(
			array(
				'author' => isset( $request['seller_id'] ) ? (int) $request['seller_id'] : 0,
				'limit'  => isset( $request['per_page'] ) ? $request['per_page'] : 10,
				'page'   => isset( $request['page'] ) ? $request['page'] : 1,
			)
		);

		$data_objects = array();

		foreach ( $products as $product ) {
			$data           = $this->prepare_data_for_response( $product, $request );
			$data_objects[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $data_objects );
		$response = $this->format_collection_response( $response, $request, count( $data_objects ) );

		return $response;
	}

	/**
	 * Get featured products
	 *
	 * @param WP_Rest_Request $request Rest request.
	 * @return array
	 */
	public function get_featured_products( $request ) {
		$products = array();
		$products = superstore()->product->get_featured_products(
			array(
				'author' => isset( $request['seller_id'] ) ? (int) $request['seller_id'] : 0,
				'limit'  => isset( $request['per_page'] ) ? $request['per_page'] : 10,
				'page'   => isset( $request['page'] ) ? $request['page'] : 1,
			)
		);

		$data_objects = array();

		foreach ( $products as $product ) {
			$data           = $this->prepare_data_for_response( $product, $request );
			$data_objects[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $data_objects );
		$response = $this->format_collection_response( $response, $request, count( $data_objects ) );

		return $response;
	}

	/**
	 * Get top rated products
	 *
	 * @param WP_Rest_Request $request Rest request.
	 * @return array
	 */
	public function get_top_rated_products( $request ) {
		$products = array();
		$products = superstore()->product->get_top_rated_products(
			array(
				'author' => isset( $request['seller_id'] ) ? (int) $request['seller_id'] : 0,
				'limit'  => isset( $request['per_page'] ) ? $request['per_page'] : 10,
				'page'   => isset( $request['page'] ) ? $request['page'] : 1,
			)
		);

		$data_objects = array();

		foreach ( $products as $product ) {
			$data           = $this->prepare_data_for_response( $product, $request );
			$data_objects[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $data_objects );
		$response = $this->format_collection_response( $response, $request, count( $data_objects ) );

		return $response;
	}

	/**
	 * Get latest products
	 *
	 * @param WP_Rest_Request $request Rest request.
	 * @return array
	 */
	public function get_latest_products( $request ) {
		$products = array();
		$products = superstore()->product->get_latest_products(
			array(
				'author' => isset( $request['seller_id'] ) ? (int) $request['seller_id'] : 0,
				'limit'  => isset( $request['per_page'] ) ? $request['per_page'] : 10,
				'page'   => isset( $request['page'] ) ? $request['page'] : 1,
			)
		);

		$data_objects = array();

		foreach ( $products as $product ) {
			$data           = $this->prepare_data_for_response( $product, $request );
			$data_objects[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $data_objects );
		$response = $this->format_collection_response( $response, $request, count( $data_objects ) );

		return $response;
	}

	/**
	 * Get product data.
	 *
	 * @param WC_Product $product Product instance.
	 * @param string     $request Request context.
	 * @return array
	 */
	protected function prepare_data_for_response( $product, $request ) {
		$context   = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$author_id = get_post_field( 'post_author', $product->get_id() );
		$seller    = superstore()->seller->crud_seller( $author_id );
		$data      = array(
			'id'                    => $product->get_id(),
			'name'                  => $product->get_name( $context ),
			'slug'                  => $product->get_slug( $context ),
			'post_author'           => $author_id,
			'permalink'             => $product->get_permalink(),
			'date_created'          => wc_rest_prepare_date_response( $product->get_date_created( $context ), false ),
			'date_created_gmt'      => wc_rest_prepare_date_response( $product->get_date_created( $context ) ),
			'date_modified'         => wc_rest_prepare_date_response( $product->get_date_modified( $context ), false ),
			'date_modified_gmt'     => wc_rest_prepare_date_response( $product->get_date_modified( $context ) ),
			'type'                  => $product->get_type(),
			'status'                => $product->get_status( $context ),
			'featured'              => $product->is_featured(),
			'catalog_visibility'    => $product->get_catalog_visibility( $context ),
			'description'           => $product->get_description( $context ),
			'short_description'     => $product->get_short_description( $context ),
			'sku'                   => $product->get_sku( $context ),
			'price'                 => $product->get_price( $context ),
			'regular_price'         => $product->get_regular_price( $context ),
			'sale_price'            => $product->get_sale_price( $context ),
			'date_on_sale_from'     => wc_rest_prepare_date_response( $product->get_date_on_sale_from( $context ), false ),
			'date_on_sale_from_gmt' => wc_rest_prepare_date_response( $product->get_date_on_sale_from( $context ) ),
			'date_on_sale_to'       => wc_rest_prepare_date_response( $product->get_date_on_sale_to( $context ), false ),
			'date_on_sale_to_gmt'   => wc_rest_prepare_date_response( $product->get_date_on_sale_to( $context ) ),
			'price_html'            => $product->get_price_html(),
			'on_sale'               => $product->is_on_sale( $context ),
			'purchasable'           => $product->is_purchasable(),
			'total_sales'           => $product->get_total_sales( $context ),
			'virtual'               => $product->is_virtual(),
			'downloadable'          => $product->is_downloadable(),
			'downloads'             => $this->get_downloads( $product ),
			'download_limit'        => $product->get_download_limit( $context ),
			'download_expiry'       => $product->get_download_expiry( $context ),
			'external_url'          => $product->is_type( 'external' ) ? $product->get_product_url( $context ) : '',
			'button_text'           => $product->is_type( 'external' ) ? $product->get_button_text( $context ) : '',
			'tax_status'            => $product->get_tax_status( $context ),
			'tax_class'             => $product->get_tax_class( $context ),
			'manage_stock'          => $product->managing_stock(),
			'stock_quantity'        => $product->get_stock_quantity( $context ),
			'low_stock_amount'      => version_compare( WC_VERSION, '3.4.7', '>' ) ? $product->get_low_stock_amount( $context ) : '',
			'in_stock'              => $product->is_in_stock(),
			'backorders'            => $product->get_backorders( $context ),
			'backorders_allowed'    => $product->backorders_allowed(),
			'backordered'           => $product->is_on_backorder(),
			'sold_individually'     => $product->is_sold_individually(),
			'weight'                => $product->get_weight( $context ),
			'dimensions'            => array(
				'length' => $product->get_length( $context ),
				'width'  => $product->get_width( $context ),
				'height' => $product->get_height( $context ),
			),
			'shipping_required'     => $product->needs_shipping(),
			'shipping_taxable'      => $product->is_shipping_taxable(),
			'shipping_class'        => $product->get_shipping_class(),
			'shipping_class_id'     => $product->get_shipping_class_id( $context ),
			'reviews_allowed'       => $product->get_reviews_allowed( $context ),
			'average_rating'        => 'view' === $context ? wc_format_decimal( $product->get_average_rating(), 2 ) : $product->get_average_rating( $context ),
			'rating_html'           => wc_get_rating_html( $product->get_average_rating() ),
			'rating_count'          => $product->get_rating_count(),
			'related_ids'           => array_map( 'absint', array_values( wc_get_related_products( $product->get_id() ) ) ),
			'upsell_ids'            => array_map( 'absint', $product->get_upsell_ids( $context ) ),
			'cross_sell_ids'        => array_map( 'absint', $product->get_cross_sell_ids( $context ) ),
			'parent_id'             => $product->get_parent_id( $context ),
			'purchase_note'         => $product->get_purchase_note( $context ),
			'categories'            => $this->get_taxonomy_terms( $product ),
			'tags'                  => $this->get_taxonomy_terms( $product, 'tag' ),
			'gallery_images'        => $this->get_images( $product ),
			'thumbnail_image'       => $this->get_image( $product ),
			'attributes'            => $this->get_attributes( $product ),
			'default_attributes'    => $this->get_default_attributes( $product ),
			'variations'            => array(),
			'grouped_products'      => array(),
			'menu_order'            => $product->get_menu_order( $context ),
			'sold'                  => $product->get_total_sales(),
			'views'                 => $product->get_meta( 'product_views' ) ? $product->get_meta( 'product_views' ) : 0,
			'meta_data'             => $product->get_meta_data(),
			'seller'                => array(
				'id'                  => $seller->get_id(),
				'name'                => $seller->get_store_name(),
				'url'                 => $seller->get_store_url(),
				'profile_picture_src' => $seller->get_profile_picture_src(),
				'address'             => $seller->get_address(),
			),
		);

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $product, $request ) );
		return apply_filters( "superstore_rest_prepare_{$this->post_type}_object", $response, $product, $request );
	}

	/**
	 * Get downloads data.
	 *
	 * @param WC_Product $product Product instance.
	 * @return array
	 */
	protected function get_downloads( $product ) {
		$downloads = array();

		if ( $product->is_downloadable() ) {
			foreach ( $product->get_downloads() as $file_id => $file ) {
				$downloads[] = array(
					'id'   => $file_id, // MD5 hash.
					'name' => $file['name'],
					'file' => $file['file'],
				);
			}
		}

		return $downloads;
	}

	/**
	 * Get taxonomy terms.
	 *
	 * @param WC_Product $product  Product instance.
	 * @param string     $taxonomy Taxonomy slug.
	 * @return array
	 */
	protected function get_taxonomy_terms( $product, $taxonomy = 'cat' ) {
		$terms = array();

		foreach ( wc_get_object_terms( $product->get_id(), 'product_' . $taxonomy ) as $term ) {
			$terms[] = array(
				'id'   => $term->term_id,
				'name' => $term->name,
				'slug' => $term->slug,
			);
		}

		return $terms;
	}

	/**
	 * Get the images for a product or product variation.
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 * @return array
	 */
	protected function get_image( $product ) {
		$image = array();
		// Set a placeholder image if the product has no images set.
		if ( ! has_post_thumbnail( $product->get_id() ) ) {
			$image = array(
				'id'                => 0,
				'date_created'      => wc_rest_prepare_date_response( current_time( 'mysql' ), false ), // Default to now.
				'date_created_gmt'  => wc_rest_prepare_date_response( time() ), // Default to now.
				'date_modified'     => wc_rest_prepare_date_response( current_time( 'mysql' ), false ),
				'date_modified_gmt' => wc_rest_prepare_date_response( time() ),
				'url'               => wc_placeholder_img_src(),
				'name'              => __( 'Placeholder', 'superstore' ),
				'alt'               => __( 'Placeholder', 'superstore' ),
				'position'          => 0,
			);
		} else {
			$attachment_post = get_post( $product->get_image_id() );
			$image           = array(
				'id'                => $product->get_image_id(),
				'url'               => wp_get_attachment_image_src( $product->get_image_id() )[0],
				'date_created'      => wc_rest_prepare_date_response( $attachment_post->post_date, false ),
				'date_created_gmt'  => wc_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
				'date_modified'     => wc_rest_prepare_date_response( $attachment_post->post_modified, false ),
				'date_modified_gmt' => wc_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
				'name'              => get_the_title( $product->get_image_id() ),
				'alt'               => get_post_meta( $product->get_image_id(), '_wp_attachment_image_alt', true ),
			);
		}

		return $image;
	}

	/**
	 * Get the images for a product or product variation.
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 * @return array
	 */
	protected function get_images( $product ) {
		$images         = array();
		$attachment_ids = array();

		// Add gallery images.
		$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		// Build image data.
		foreach ( $attachment_ids as $position => $attachment_id ) {
			$attachment_post = get_post( $attachment_id );
			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'id'                => (int) $attachment_id,
				'date_created'      => wc_rest_prepare_date_response( $attachment_post->post_date, false ),
				'date_created_gmt'  => wc_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
				'date_modified'     => wc_rest_prepare_date_response( $attachment_post->post_modified, false ),
				'date_modified_gmt' => wc_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
				'url'               => current( $attachment ),
				'name'              => get_the_title( $attachment_id ),
				'alt'               => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);
		}

		return $images;
	}

	/**
	 * Get the attributes for a product or product variation.
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 * @return array
	 */
	protected function get_attributes( $product ) {
		$attributes = array();

		if ( $product->is_type( 'variation' ) ) {
			$_product = wc_get_product( $product->get_parent_id() );
			foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {
				$name = str_replace( 'attribute_', '', $attribute_name );

				if ( ! $attribute ) {
					continue;
				}

				// Taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`.
				if ( 0 === strpos( $attribute_name, 'attribute_pa_' ) ) {
					$option_term  = get_term_by( 'slug', $attribute, $name );
					$attributes[] = array(
						'id'     => wc_attribute_taxonomy_id_by_name( $name ),
						'name'   => $this->get_attribute_taxonomy_name( $name, $_product ),
						'option' => $option_term && ! is_wp_error( $option_term ) ? $option_term->name : $attribute,
					);
				} else {
					$attributes[] = array(
						'id'     => 0,
						'name'   => $this->get_attribute_taxonomy_name( $name, $_product ),
						'option' => $attribute,
					);
				}
			}
		} else {
			foreach ( $product->get_attributes() as $attribute ) {
				$attributes[] = array(
					'id'        => $attribute['is_taxonomy'] ? wc_attribute_taxonomy_id_by_name( $attribute['name'] ) : 0,
					'name'      => $this->get_attribute_taxonomy_name( $attribute['name'], $product ),
					'position'  => (int) $attribute['position'],
					'visible'   => (bool) $attribute['is_visible'],
					'variation' => (bool) $attribute['is_variation'],
					'options'   => $this->get_attribute_options( $product->get_id(), $attribute ),
				);
			}
		}

		return $attributes;
	}

	/**
	 * Get default attributes.
	 *
	 * @param WC_Product $product Product instance.
	 * @return array
	 */
	protected function get_default_attributes( $product ) {
		$default = array();

		if ( $product->is_type( 'variable' ) ) {
			foreach ( array_filter( (array) $product->get_default_attributes(), 'strlen' ) as $key => $value ) {
				if ( 0 === strpos( $key, 'pa_' ) ) {
					$default[] = array(
						'id'     => wc_attribute_taxonomy_id_by_name( $key ),
						'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
						'option' => $value,
					);
				} else {
					$default[] = array(
						'id'     => 0,
						'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
						'option' => $value,
					);
				}
			}
		}

		return $default;
	}

	/**
	 * Get attribute taxonomy label.
	 *
	 * @param  string $name Taxonomy name.
	 * @return string
	 */
	protected function get_attribute_taxonomy_label( $name ) {
		$tax    = get_taxonomy( $name );
		$labels = get_taxonomy_labels( $tax );

		return $labels->singular_name;
	}

	/**
	 * Get product attribute taxonomy name.
	 *
	 * @param  string     $slug    Taxonomy name.
	 * @param  WC_Product $product Product data.
	 * @return string
	 */
	protected function get_attribute_taxonomy_name( $slug, $product ) {
		$attributes = $product->get_attributes();

		if ( ! isset( $attributes[ $slug ] ) ) {
			return str_replace( 'pa_', '', $slug );
		}

		$attribute = $attributes[ $slug ];

		// Taxonomy attribute name.
		if ( $attribute->is_taxonomy() ) {
			$taxonomy = $attribute->get_taxonomy_object();
			return $taxonomy->attribute_label;
		}

		// Custom product attribute name.
		return $attribute->get_name();
	}

	/**
	 * Get attribute options.
	 *
	 * @param int   $product_id Product ID.
	 * @param array $attribute  Attribute data.
	 * @return array
	 */
	protected function get_attribute_options( $product_id, $attribute ) {
		if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {
			return wc_get_product_terms(
				$product_id,
				$attribute['name'],
				array(
					'fields' => 'names',
				)
			);
		} elseif ( isset( $attribute['value'] ) ) {
			return array_map( 'trim', explode( '|', $attribute['value'] ) );
		}

		return array();
	}

	/**
	 * Search downloadable products for oreder grant new permission.
	 *
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_downloadable_products( $request ) {
		$args = array(
			'author'       => get_current_user_id(),
			'limit'        => -1,
			'status'       => 'publish',
			'downloadable' => true,
		);

		$objects  = wc_get_products( $args );
		$products = array();

		foreach ( $objects as $key => $product ) {
			if ( $product && $product->exists() && $product->is_downloadable() && ! empty( $product->get_downloads() ) ) {
				$formatted_data['id']             = $product->get_id();
				$formatted_data['formatted_name'] = $product->get_formatted_name();
				$products[]                       = $formatted_data;
			} else {
				$formatted_data['id']             = $product->get_id();
				$formatted_data['formatted_name'] = $product->get_formatted_name() . __( ' (No File)' );
				$products[]                       = $formatted_data;
			}
		}

		return rest_ensure_response( $products );
	}

	/**
	 * Prepare object for database mapping
	 *
	 * @param object  $request Request.
	 * @param boolean $creating Creating.
	 *
	 * @return object
	 * @throws WC_REST_Exception On error.
	 * @throws \WC_Data_Exception On error.
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		// Type is the most important part here because we need to be using the correct class and methods.
		if ( isset( $request['type'] ) ) {
			$classname = WC_Product_Factory::get_classname_from_product_type( $request['type'] );

			if ( ! class_exists( $classname ) ) {
				$classname = 'WC_Product_Simple';
			}

			$product = new $classname( $id );
		} elseif ( isset( $request['id'] ) ) {
			$product = wc_get_product( $id );
		} else {
			$product = new WC_Product_Simple();
		}

		if ( 'variation' === $product->get_type() ) {
			return new WP_Error(
				"superstore_rest_invalid_{$this->post_type}_id",
				__( 'To manipulate product variations you should use the /products/&lt;product_id&gt;/variations/&lt;id&gt; endpoint.', 'superstore' ),
				array(
					'status' => 404,
				)
			);
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$product->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$product->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post excerpt.
		if ( isset( $request['short_description'] ) ) {
			$product->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		$seller = superstore()->seller->crud_seller( get_current_user_id() );

		// Post status.
		if ( ! $id ) {
			if ( 'no' === $seller->get_requires_product_review() ) {
				if ( isset( $request['status'] ) ) {
					$product->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'publish' );
				}
			} else {
				$status = superstore_get_option( 'seller_new_product_status', 'superstore_seller', 'pending' );
				$product->set_status( get_post_status_object( $status ) ? $status : 'draft' );
			}
		} else {
			if ( 'pending' !== $product->get_status() ) {
				if ( isset( $request['status'] ) ) {
					$product->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : $product->get_status() );
				}
			}
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$product->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$product->set_menu_order( $request['menu_order'] );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$product->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Virtual.
		if ( isset( $request['virtual'] ) ) {
			$product->set_virtual( $request['virtual'] );
		}

		// Tax status.
		if ( isset( $request['tax_status'] ) ) {
			$product->set_tax_status( $request['tax_status'] );
		}

		// Tax Class.
		if ( isset( $request['tax_class'] ) ) {
			$product->set_tax_class( $request['tax_class'] );
		}

		// Catalog Visibility.
		if ( isset( $request['catalog_visibility'] ) ) {
			$product->set_catalog_visibility( $request['catalog_visibility'] );
		}

		// Purchase Note.
		if ( isset( $request['purchase_note'] ) ) {
			$product->set_purchase_note( wc_clean( $request['purchase_note'] ) );
		}

		// Featured Product.
		if ( isset( $request['featured'] ) ) {
			$product->set_featured( $request['featured'] );
		}

		// Shipping data.
		$product = $this->save_product_shipping_data( $product, $request );

		// SKU.
		if ( isset( $request['sku'] ) ) {
			$product->set_sku( wc_clean( $request['sku'] ) );
		}

		// Attributes.
		if ( isset( $request['attributes'] ) ) {
			$attributes = array();

			foreach ( $request['attributes'] as $attribute ) {
				$attribute_id   = 0;
				$attribute_name = '';

				// Check ID for global attributes or name for product attributes.
				if ( ! empty( $attribute['id'] ) ) {
					$attribute_id   = absint( $attribute['id'] );
					$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
				} elseif ( ! empty( $attribute['name'] ) ) {
					$attribute_name = wc_clean( $attribute['name'] );
				}

				if ( ! $attribute_id && ! $attribute_name ) {
					continue;
				}

				if ( $attribute_id ) {
					if ( isset( $attribute['options'] ) ) {
						$options = $attribute['options'];

						if ( ! is_array( $attribute['options'] ) ) {
							// Text based attributes - Posted values are term names.
							$options = explode( WC_DELIMITER, $options );
						}

						$values = array_map( 'wc_sanitize_term_text_based', $options );
						$values = array_filter( $values, 'strlen' );
					} else {
						$values = array();
					}

					if ( ! empty( $values ) ) {
						// Add attribute to array, but don't set values.
						$attribute_object = new WC_Product_Attribute();
						$attribute_object->set_id( $attribute_id );
						$attribute_object->set_name( $attribute_name );
						$attribute_object->set_options( $values );
						$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
						$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
						$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
						$attributes[] = $attribute_object;
					}
				} elseif ( isset( $attribute['options'] ) ) {
					// Custom attribute - Add attribute to array and set the values.
					if ( is_array( $attribute['options'] ) ) {
						$values = $attribute['options'];
					} else {
						$values = explode( WC_DELIMITER, $attribute['options'] );
					}
					$attribute_object = new WC_Product_Attribute();
					$attribute_object->set_name( $attribute_name );
					$attribute_object->set_options( $values );
					$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
					$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
					$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
					$attributes[] = $attribute_object;
				}
			}
			$product->set_attributes( $attributes );
		}

		// Sales and prices.
		if ( in_array( $product->get_type(), array( 'variable', 'grouped' ), true ) ) {
			$product->set_regular_price( '' );
			$product->set_sale_price( '' );
			$product->set_date_on_sale_to( '' );
			$product->set_date_on_sale_from( '' );
			$product->set_price( '' );
		} else {
			// Regular Price.
			if ( isset( $request['regular_price'] ) ) {
				$product->set_regular_price( $request['regular_price'] );
			}

			// Sale Price.
			if ( isset( $request['sale_price'] ) ) {
				$product->set_sale_price( $request['sale_price'] );
			}

			if ( isset( $request['date_on_sale_from'] ) ) {
				$product->set_date_on_sale_from( $request['date_on_sale_from'] );
			}

			if ( isset( $request['date_on_sale_from_gmt'] ) ) {
				$product->set_date_on_sale_from( $request['date_on_sale_from_gmt'] ? strtotime( $request['date_on_sale_from_gmt'] ) : null );
			}

			if ( isset( $request['date_on_sale_to'] ) ) {
				$product->set_date_on_sale_to( $request['date_on_sale_to'] );
			}

			if ( isset( $request['date_on_sale_to_gmt'] ) ) {
				$product->set_date_on_sale_to( $request['date_on_sale_to_gmt'] ? strtotime( $request['date_on_sale_to_gmt'] ) : null );
			}
		}

		// Product parent ID for groups.
		if ( isset( $request['parent_id'] ) ) {
			$product->set_parent_id( $request['parent_id'] );
		}

		// Sold individually.
		if ( isset( $request['sold_individually'] ) ) {
			$product->set_sold_individually( $request['sold_individually'] );
		}

		// Stock status.
		if ( isset( $request['in_stock'] ) ) {
			$stock_status = true === $request['in_stock'] ? 'instock' : 'outofstock';
		} else {
			$stock_status = $product->get_stock_status();
		}

		// Stock data.
		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			// Manage stock.
			if ( isset( $request['manage_stock'] ) ) {
				$product->set_manage_stock( $request['manage_stock'] );
			}

			// Backorders.
			if ( isset( $request['backorders'] ) ) {
				$product->set_backorders( $request['backorders'] );
			}

			if ( $product->is_type( 'grouped' ) ) {
				$product->set_manage_stock( 'no' );
				$product->set_backorders( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( $stock_status );

				if ( version_compare( WC_VERSION, '3.4.7', '>' ) ) {
					$product->set_low_stock_amount( '' );
				}
			} elseif ( $product->is_type( 'external' ) ) {
				$product->set_manage_stock( 'no' );
				$product->set_backorders( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( 'instock' );

				if ( version_compare( WC_VERSION, '3.4.7', '>' ) ) {
					$product->set_low_stock_amount( '' );
				}
			} elseif ( $product->get_manage_stock() ) {
				// Stock status is always determined by children so sync later.
				if ( ! $product->is_type( 'variable' ) ) {
					$product->set_stock_status( $stock_status );
				}

				// Stock quantity.
				if ( isset( $request['stock_quantity'] ) ) {
					$product->set_stock_quantity( wc_stock_amount( $request['stock_quantity'] ) );
				} elseif ( isset( $request['inventory_delta'] ) ) {
					$stock_quantity  = wc_stock_amount( $product->get_stock_quantity() );
					$stock_quantity += wc_stock_amount( $request['inventory_delta'] );
					$product->set_stock_quantity( wc_stock_amount( $stock_quantity ) );
				}

				if ( version_compare( WC_VERSION, '3.4.7', '>' ) && isset( $request['low_stock_amount'] ) ) {
					$product->set_low_stock_amount( wc_stock_amount( $request['low_stock_amount'] ) );
				}
			} else {
				// Don't manage stock.
				$product->set_manage_stock( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( $stock_status );
			}
		} elseif ( ! $product->is_type( 'variable' ) ) {
			$product->set_stock_status( $stock_status );
		}

		// Upsells.
		if ( isset( $request['upsell_ids'] ) ) {
			$upsells = array();
			$ids     = $request['upsell_ids'];

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$upsells[] = $id;
					}
				}
			}

			$product->set_upsell_ids( $upsells );
		}

		// Cross sells.
		if ( isset( $request['cross_sell_ids'] ) ) {
			$crosssells = array();
			$ids        = $request['cross_sell_ids'];

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$crosssells[] = $id;
					}
				}
			}

			$product->set_cross_sell_ids( $crosssells );
		}

		// Product categories.
		if ( isset( $request['categories'] ) && is_array( $request['categories'] ) ) {
			$product = $this->save_taxonomy_terms( $product, $request['categories'] );
		}

		// Product tags.
		if ( isset( $request['tags'] ) && is_array( $request['tags'] ) ) {
			$product = $this->save_taxonomy_terms( $product, $request['tags'], 'tag' );
		}

		// Downloadable.
		if ( isset( $request['downloadable'] ) ) {
			$product->set_downloadable( $request['downloadable'] );
		}

		// Downloadable options.
		if ( $product->get_downloadable() ) {

			// Downloadable files.
			if ( isset( $request['downloads'] ) && is_array( $request['downloads'] ) ) {
				$product = $this->save_downloadable_files( $product, $request['downloads'] );
			}

			// Download limit.
			if ( isset( $request['download_limit'] ) ) {
				$product->set_download_limit( $request['download_limit'] );
			}

			// Download expiry.
			if ( isset( $request['download_expiry'] ) ) {
				$product->set_download_expiry( $request['download_expiry'] );
			}
		}

		// Product url and button text for external products.
		if ( $product->is_type( 'external' ) ) {
			if ( isset( $request['external_url'] ) ) {
				$product->set_product_url( $request['external_url'] );
			}

			if ( isset( $request['button_text'] ) ) {
				$product->set_button_text( $request['button_text'] );
			}
		}

		// Save default attributes for variable products.
		if ( $product->is_type( 'variable' ) ) {
			$product = $this->save_default_attributes( $product, $request );
		}

		// Set children for a grouped product.
		if ( $product->is_type( 'grouped' ) && isset( $request['grouped_products'] ) ) {
			$product->set_children( $request['grouped_products'] );
		}

		// Set featured/gallery images.
		if ( isset( $request['gallery_images'] ) ) {
			$product = $this->set_gallery_images( $product, $request['gallery_images'] );
		}

		// Set product image.
		if ( isset( $request['thumbnail_image'] ) ) {
			$product = $this->set_thumbnail_image( $product, $request['thumbnail_image'] );
		}

		// Allow set meta_data.
		if ( is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$product->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->post_type`,
		 * refers to the object type slug.
		 *
		 * @param WC_Data $product Object object.
		 * @param WP_REST_Request $request Request object.
		 * @param bool $creating If is creating a new object.
		 */
		return apply_filters( "woocommerce_superstore_rest_pre_insert_{$this->post_type}_object", $product, $request, $creating );
	}

	/**
	 * Save product shipping data.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $data Shipping data.
	 *
	 * @return WC_Product
	 */
	protected function save_product_shipping_data( $product, $data ) {
		// Virtual.
		if ( isset( $data['virtual'] ) && true === $data['virtual'] ) {
			$product->set_weight( '' );
			$product->set_height( '' );
			$product->set_length( '' );
			$product->set_width( '' );
		} else {
			if ( isset( $data['weight'] ) ) {
				$product->set_weight( $data['weight'] );
			}

			// Height.
			if ( isset( $data['dimensions']['height'] ) ) {
				$product->set_height( $data['dimensions']['height'] );
			}

			// Width.
			if ( isset( $data['dimensions']['width'] ) ) {
				$product->set_width( $data['dimensions']['width'] );
			}

			// Length.
			if ( isset( $data['dimensions']['length'] ) ) {
				$product->set_length( $data['dimensions']['length'] );
			}
		}

		// Shipping class.
		if ( isset( $data['shipping_class'] ) ) {
			$data_store        = $product->get_data_store();
			$shipping_class_id = $data_store->get_shipping_class_id_by_slug( wc_clean( $data['shipping_class'] ) );
			$product->set_shipping_class_id( $shipping_class_id );
		}

		return $product;
	}

	/**
	 * Save taxonomy terms.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $terms Terms data.
	 * @param string     $taxonomy Taxonomy name.
	 *
	 * @return WC_Product
	 */
	protected function save_taxonomy_terms( $product, $terms, $taxonomy = 'cat' ) {
		$term_ids = wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$product->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$product->set_tag_ids( $term_ids );
		}

		return $product;
	}

	/**
	 * Save downloadable files.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $downloads Downloads data.
	 * @param int        $deprecated Deprecated since 3.0.
	 *
	 * @return WC_Product
	 */
	protected function save_downloadable_files( $product, $downloads, $deprecated = 0 ) {
		if ( $deprecated ) {
			wc_deprecated_argument( 'variation_id', '3.0', 'save_downloadable_files() not requires a variation_id anymore.' );
		}

		$files = array();
		foreach ( $downloads as $key => $file ) {
			if ( empty( $file['file'] ) ) {
				continue;
			}

			$download = new WC_Product_Download();
			$download->set_id( md5( $file['file'] ) );
			$download->set_name( $file['name'] ? $file['name'] : wc_get_filename_from_url( $file['file'] ) );
			$download->set_file( apply_filters( 'woocommerce_file_download_path', $file['file'], $product, $key ) );
			$files[] = $download;
		}
		$product->set_downloads( $files );

		return $product;
	}

	/**
	 * Save default attributes.
	 *
	 * @param WC_Product      $product Product instance.
	 * @param WP_REST_Request $request Request data.
	 * @return WC_Product
	 */
	protected function save_default_attributes( $product, $request ) {
		if ( isset( $request['default_attributes'] ) && is_array( $request['default_attributes'] ) ) {
			$attributes         = $product->get_attributes();
			$default_attributes = array();

			foreach ( $request['default_attributes'] as $attribute ) {
				$attribute_id   = 0;
				$attribute_name = '';

				// Check ID for global attributes or name for product attributes.
				if ( ! empty( $attribute['id'] ) ) {
					$attribute_id   = absint( $attribute['id'] );
					$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
				} elseif ( ! empty( $attribute['name'] ) ) {
					$attribute_name = sanitize_title( $attribute['name'] );
				}

				if ( ! $attribute_id && ! $attribute_name ) {
					continue;
				}

				if ( isset( $attributes[ $attribute_name ] ) ) {
					$_attribute = $attributes[ $attribute_name ];

					if ( $_attribute['is_variation'] ) {
						$value = isset( $attribute['option'] ) ? wc_clean( stripslashes( $attribute['option'] ) ) : '';

						if ( ! empty( $_attribute['is_taxonomy'] ) ) {
							// If dealing with a taxonomy, we need to get the slug from the name posted to the API.
							$term = get_term_by( 'name', $value, $attribute_name );

							if ( $term && ! is_wp_error( $term ) ) {
								$value = $term->slug;
							} else {
								$value = sanitize_title( $value );
							}
						}

						if ( $value ) {
							$default_attributes[ $attribute_name ] = $value;
						}
					}
				}
			}

			$product->set_default_attributes( $default_attributes );
		}

		return $product;
	}

	/**
	 * Set product image.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $image Image data.
	 * @return WC_Product
	 * @throws WC_REST_Exception REST API exceptions.
	 */
	public function set_thumbnail_image( $product, $image ) {
		if ( ! empty( $image['id'] ) && ! wp_attachment_is_image( $image['id'] ) ) {
			/* translators: %s: attachment id */
			throw new WC_REST_Exception( 'woocommerce_product_invalid_image_id', sprintf( __( '#%s is an invalid image ID.', 'superstore' ), $attachment_id ), 400 );
		}

		$product->set_image_id( $image['id'] );

		// Set the image alt if present.
		if ( ! empty( $image['alt'] ) ) {
			update_post_meta( $image['id'], '_wp_attachment_image_alt', wc_clean( $image['alt'] ) );
		}

		// Set the image name if present.
		if ( ! empty( $image['name'] ) ) {
			wp_update_post(
				array(
					'ID'         => $image['id'],
					'post_title' => $image['name'],
				)
			);
		}

		// Set the image source if present, for future reference.
		if ( ! empty( $image['url'] ) ) {
			update_post_meta( $image['id'], '_wc_attachment_source', esc_url_raw( $image['url'] ) );
		}

		return $product;
	}

	/**
	 * Set product images.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $images Images data.
	 * @return WC_Product
	 * @throws WC_REST_Exception REST API exceptions.
	 */
	protected function set_gallery_images( $product, $images ) {
		$images = is_array( $images ) ? array_filter( $images ) : array();

		if ( ! empty( $images ) ) {
			$gallery = array();

			foreach ( $images as $image ) {
				$attachment_id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;

				if ( 0 === $attachment_id && isset( $image['url'] ) ) {
					$upload = wc_rest_upload_image_from_url( esc_url_raw( $image['url'] ) );

					if ( is_wp_error( $upload ) ) {
						if ( ! apply_filters( 'woocommerce_rest_suppress_image_upload_error', false, $upload, $product->get_id(), $images ) ) {
							throw new WC_REST_Exception( 'woocommerce_product_image_upload_error', $upload->get_error_message(), 400 );
						} else {
							continue;
						}
					}

					$attachment_id = wc_rest_set_uploaded_image_as_attachment( $upload, $product->get_id() );
				}

				if ( ! wp_attachment_is_image( $attachment_id ) ) {
					/* translators: %s: attachment id */
					throw new WC_REST_Exception( 'woocommerce_product_invalid_image_id', sprintf( __( '#%s is an invalid image ID.', 'superstore' ), $attachment_id ), 400 );
				}

				$gallery[] = $attachment_id;

				// Set the image alt if present.
				if ( ! empty( $image['alt'] ) ) {
					update_post_meta( $attachment_id, '_wp_attachment_image_alt', wc_clean( $image['alt'] ) );
				}

				// Set the image name if present.
				if ( ! empty( $image['name'] ) ) {
					wp_update_post(
						array(
							'ID'         => $attachment_id,
							'post_title' => $image['name'],
						)
					);
				}

				// Set the image source if present, for future reference.
				if ( ! empty( $image['url'] ) ) {
					update_post_meta( $attachment_id, '_wc_attachment_source', esc_url_raw( $image['url'] ) );
				}
			}

			$product->set_gallery_image_ids( $gallery );
		} else {
			$product->set_image_id( '' );
			$product->set_gallery_image_ids( array() );
		}

		return $product;
	}

	/**
	 * Validation before get product
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool|WP_Error
	 */
	public function validation_before_get_item( $request ) {
		$store_id = get_current_user_id();

		if ( empty( $store_id ) ) {
			return new WP_Error( 'superstore_no_order_seller_found', __( 'No seller found', 'superstore' ), array( 'status' => 406 ) );
		}

		$object = $this->get_object( (int) $request['id'] );

		$product_author = (int) superstore_get_seller_by_product( $object )->get_id();

		if ( $store_id !== $product_author ) {
			return new WP_Error( "superstore_rest_{$this->post_type}_invalid_id", __( 'This is not your product', 'superstore' ), array( 'status' => 406 ) );
		}

		return true;
	}

	/**
	 * Validation before create product
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool|WP_Error
	 */
	public function validation_before_create_item( $request ) {
		$store_id = get_current_user_id();

		if ( empty( $store_id ) ) {
			return new WP_Error( 'superstore_no_product_seller_found', __( 'No seller found', 'superstore' ), array( 'status' => 402 ) );
		}

		$author_is_seller = user_can( $store_id, 'manage_superstore' );

		if ( ! $author_is_seller ) {
			return new WP_Error( 'superstore_user_is_not_seller', __( 'This user is not a seller', 'superstore' ) );
		}

		if ( ! empty( $request['id'] ) ) {
			/* translators: %s: product */
			return new WP_Error( "woocommerce_rest_{$this->post_type}_exists", sprintf( __( 'Cannot create existing %s.', 'superstore' ), 'product' ), array( 'status' => 402 ) );
		}

		$enabled                            = superstore()->seller->crud_seller( $store_id )->get_enabled();
		$disabled_ac_add_product_permission = superstore_get_option( 'disabled_seller_can', 'superstore_seller', array( 'add_product' => 'no' ) );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			if ( 'yes' !== $enabled ) {
				if ( 'yes' !== $disabled_ac_add_product_permission['add_product'] ) {
					return new WP_Error( 'superstore_product_seller_not_enabled', __( 'Account is not enabled.', 'superstore' ), array( 'status' => 402 ) );
				}
			}
		}

		if ( empty( $request['name'] ) ) {
			return new WP_Error( 'superstore_no_product_name_found', sprintf( __( 'Product name is required', 'superstore' ), 'product' ), array( 'status' => 402 ) );
		}

		$category_selection = superstore_get_option( 'seller_can_add_multiple_categories', 'superstore_seller', 'no' );

		if ( empty( $request['categories'] ) ) {
			return new WP_Error( 'superstore_no_product_category_found', __( 'Category is required', 'superstore' ), array( 'status' => 402 ) );
		}

		if ( 'no' === $category_selection ) {
			if ( count( $request['categories'] ) > 1 ) {
				return new WP_Error( 'superstore_no_multiple_product_categories', __( 'Can not add more than one category', 'superstore' ), array( 'status' => 402 ) );
			}
		}

		return true;
	}

	/**
	 * Validation before update product
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool|WP_Error
	 */
	public function validation_before_update_item( $request ) {
		$store_id = get_current_user_id();

		if ( empty( $store_id ) ) {
			return new WP_Error( 'superstore_no_product_seller_found', __( 'No seller found', 'superstore' ), array( 'status' => 403 ) );
		}

		$object = $this->get_object( (int) $request['id'] );

		if ( ! $object || 0 === $object->get_id() ) {
			return new WP_Error( "superstore_rest_{$this->post_type}_invalid_id", __( 'Invalid ID.', 'superstore' ), array( 'status' => 403 ) );
		}

		$product_author = (int) get_post_field( 'post_author', $object->get_id() );

		if ( $store_id !== $product_author ) {
			return new WP_Error( "superstore_rest_{$this->post_type}_invalid_id", __( 'This is not your product', 'superstore' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * Validation before delete item
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_Error|Boolean
	 */
	public function validation_before_delete_item( $request ) {
		$store_id = get_current_user_id();
		$object   = $this->get_object( (int) $request['id'] );
		$result   = false;

		if ( ! $object || 0 === $object->get_id() ) {
			return new WP_Error( "superstore_rest_{$this->post_type}_invalid_id", __( 'Invalid ID.', 'superstore' ), array( 'status' => 405 ) );
		}

		$product_author = (int) get_post_field( 'post_author', $object->get_id() );

		if ( $store_id !== $product_author ) {
			return new WP_Error( "superstore_rest_{$this->post_type}_invalid_id", __( 'This is not your product', 'superstore' ), array( 'status' => 405 ) );
		}

		$enabled                            = superstore()->seller->crud_seller( $store_id )->get_enabled();
		$disabled_ac_add_product_permission = superstore_get_option( 'disabled_seller_can', 'superstore_seller', array( 'delete_product' => 'no' ) );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			if ( 'yes' !== $enabled ) {
				if ( 'yes' !== $disabled_ac_add_product_permission['delete_product'] ) {
					return new WP_Error( 'superstore_product_seller_not_enabled', __( 'Account is not enabled.', 'superstore' ), array( 'status' => 402 ) );
				}
			}
		}

		return true;
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WC_Data         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 * @return array.
	 */
	protected function prepare_links( $object, $request ) {
		$links = array(
			'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->base, $object->get_id() ) ),
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->base ) ),
			),
		);

		if ( $object->get_parent_id() ) {
			$links['up'] = array(
				'href' => rest_url( sprintf( '/%s/products/%d', $this->namespace, $object->get_parent_id() ) ),
			);
		}

		return $links;
	}

	/**
	 * Format item's collection for response
	 *
	 * @param  object $response Response.
	 * @param  object $request Request.
	 * @param  int    $total_items Total items.
	 *
	 * @return object
	 */
	public function format_collection_response( $response, $request, $total_items ) {
		parent::format_collection_response( $response, $request, $total_items );

		$all     = count( superstore()->product->get_seller_products( get_current_user_id() ) );
		$publish = count( superstore()->product->get_seller_products( get_current_user_id(), array( 'status' => 'publish' ) ) );
		$pending = count( superstore()->product->get_seller_products( get_current_user_id(), array( 'status' => 'pending' ) ) );

		$response->header( 'superstore-all', $all );
		$response->header( 'superstore-publish', $publish );
		$response->header( 'superstore-pending', $pending );

		return $response;
	}

	/**
	 * Prepare objects query
	 *
	 * @param WP_REST_Request $request Request.
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		$filters = array();
		if ( isset( $request['filters'] ) && ! empty( $request['filters'] ) ) {
			$filters = $request['filters'];
		}

		$statuses = array();

		if ( ! empty( $filters ) && is_array( $filters ) ) {
			foreach ( $filters as $key => $value ) {
				if ( 'yes' === $value ) {
					$statuses[] = $key;
				}
			}
		}

		// Filter by post statuses.
		$args['post_status'] = ! empty( $statuses ) ? $statuses : $this->post_status;

		// phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
		$args['posts_per_page'] = isset( $request['limit'] ) ? (int) $request['limit'] : 10;

		// Taxonomy query to filter products by type, category,
		// tag, shipping class, and attribute.
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'product_cat'            => 'category',
			'product_tag'            => 'tag',
			'product_shipping_class' => 'shipping_class',
		);

		// Set tax_query for each passed arg.
		foreach ( $taxonomies as $taxonomy => $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $request[ $key ],
				);
			}
		}

		// Filter product type by slug.
		if ( ! empty( $request['type'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => $request['type'],
			);
		}

		// Filter by attribute and term.
		if ( ! empty( $request['attribute'] ) && ! empty( $request['attribute_term'] ) ) {
			if ( in_array( $request['attribute'], wc_get_attribute_taxonomy_names(), true ) ) {
				$tax_query[] = array(
					'taxonomy' => $request['attribute'],
					'field'    => 'term_id',
					'terms'    => $request['attribute_term'],
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query; //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			);
		}

		// Filter by sku.
		if ( ! empty( $request['sku'] ) ) {
			$skus = explode( ',', $request['sku'] );
			// Include the current string as a SKU too.
			if ( 1 < count( $skus ) ) {
				$skus[] = $request['sku'];
			}

			$args['meta_query'] = $this->add_meta_query( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$args,
				array(
					'key'     => '_sku',
					'value'   => $skus,
					'compare' => 'IN',
				),
			);
		}

		// Filter by tax class.
		if ( ! empty( $request['tax_class'] ) ) {
			$args['meta_query'] = $this->add_meta_query( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$args,
				array(
					'key'   => '_tax_class',
					'value' => 'standard' !== $request['tax_class'] ? $request['tax_class'] : '',
				)
			);
		}

		// Price filter.
		if ( ! empty( $request['min_price'] ) || ! empty( $request['max_price'] ) ) {
			$args['meta_query'] = $this->add_meta_query( $args, wc_get_min_max_price_meta_query( $request ) );  //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Filter product in stock or out of stock.
		if ( is_bool( $request['in_stock'] ) ) {
			$args['meta_query'] = $this->add_meta_query( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$args,
				array(
					'key'   => '_stock_status',
					'value' => true === $request['in_stock'] ? 'instock' : 'outofstock',
				)
			);
		}

		// Filter by on sale products.
		if ( is_bool( $request['on_sale'] ) ) {
			$on_sale_key = $request['on_sale'] ? 'post__in' : 'post__not_in';
			$on_sale_ids = wc_get_product_ids_on_sale();

			// Use 0 when there's no on sale products to avoid return all products.
			$on_sale_ids = empty( $on_sale_ids ) ? array( 0 ) : $on_sale_ids;

			$args[ $on_sale_key ] += $on_sale_ids;
		}

		// Force the post_type argument, since it's not a user input variable.
		if ( ! empty( $request['sku'] ) ) {
			$args['post_type'] = array( 'product', 'product_variation' );
		} else {
			$args['post_type'] = $this->post_type;
		}

		return $args;
	}

	/**
	 * Product's schema for JSON Schema validation.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$weight_unit    = get_option( 'woocommerce_weight_unit' );
		$dimension_unit = get_option( 'woocommerce_dimension_unit' );
		$schema         = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->post_type,
			'type'       => 'object',
			'properties' => array(
				'id'                    => array(
					'description' => __( 'Unique identifier for the resource.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'                  => array(
					'description' => __( 'Product name.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'                  => array(
					'description' => __( 'Product slug.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'             => array(
					'description' => __( 'Product URL.', 'superstore' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'          => array(
					'description' => __( "The date the product was created, in the site's timezone.", 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created_gmt'      => array(
					'description' => __( 'The date the product was created, as GMT.', 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified'         => array(
					'description' => __( "The date the product was last modified, in the site's timezone.", 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'     => array(
					'description' => __( 'The date the product was last modified, as GMT.', 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'type'                  => array(
					'description' => __( 'Product type.', 'superstore' ),
					'type'        => 'string',
					'default'     => 'simple',
					'enum'        => array_keys( wc_get_product_types() ),
					'context'     => array( 'view', 'edit' ),
				),
				'status'                => array(
					'description' => __( 'Product status (post status).', 'superstore' ),
					'type'        => 'string',
					'default'     => 'publish',
					'enum'        => array_keys( get_post_statuses() ),
					'context'     => array( 'view', 'edit' ),
				),
				'featured'              => array(
					'description' => __( 'Featured product.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'catalog_visibility'    => array(
					'description' => __( 'Catalog visibility.', 'superstore' ),
					'type'        => 'string',
					'default'     => 'visible',
					'enum'        => array( 'visible', 'catalog', 'search', 'hidden' ),
					'context'     => array( 'view', 'edit' ),
				),
				'description'           => array(
					'description' => __( 'Product description.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description'     => array(
					'description' => __( 'Product short description.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'sku'                   => array(
					'description' => __( 'Unique identifier.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'price'                 => array(
					'description' => __( 'Current product price.', 'superstore' ),
					'type'        => 'float',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'regular_price'         => array(
					'description' => __( 'Product regular price.', 'superstore' ),
					'type'        => 'float',
					'context'     => array( 'view', 'edit' ),
				),
				'sale_price'            => array(
					'description' => __( 'Product sale price.', 'superstore' ),
					'type'        => 'float',
					'context'     => array( 'view', 'edit' ),
				),
				'date_on_sale_from'     => array(
					'description' => __( "Start date of sale price, in the site's timezone.", 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_on_sale_from_gmt' => array(
					'description' => __( 'Start date of sale price, as GMT.', 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_on_sale_to'       => array(
					'description' => __( "End date of sale price, in the site's timezone.", 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_on_sale_to_gmt'   => array(
					'description' => __( 'End date of sale price, as GMT.', 'superstore' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'price_html'            => array(
					'description' => __( 'Price formatted in HTML.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'on_sale'               => array(
					'description' => __( 'Shows if the product is on sale.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'purchasable'           => array(
					'description' => __( 'Shows if the product can be bought.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'total_sales'           => array(
					'description' => __( 'Amount of sales.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'virtual'               => array(
					'description' => __( 'If the product is virtual.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'downloadable'          => array(
					'description' => __( 'If the product is downloadable.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'downloads'             => array(
					'description' => __( 'List of downloadable files.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'File MD5 hash.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'name' => array(
								'description' => __( 'File name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'file' => array(
								'description' => __( 'File URL.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'download_limit'        => array(
					'description' => __( 'Number of times downloadable files can be downloaded after purchase.', 'superstore' ),
					'type'        => 'integer',
					'default'     => - 1,
					'context'     => array( 'view', 'edit' ),
				),
				'download_expiry'       => array(
					'description' => __( 'Number of days until access to downloadable files expires.', 'superstore' ),
					'type'        => 'integer',
					'default'     => - 1,
					'context'     => array( 'view', 'edit' ),
				),
				'external_url'          => array(
					'description' => __( 'Product external URL. Only for external products.', 'superstore' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
				),
				'button_text'           => array(
					'description' => __( 'Product external button text. Only for external products.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'tax_status'            => array(
					'description' => __( 'Tax status.', 'superstore' ),
					'type'        => 'string',
					'default'     => 'taxable',
					'enum'        => array( 'taxable', 'shipping', 'none' ),
					'context'     => array( 'view', 'edit' ),
				),
				'tax_class'             => array(
					'description' => __( 'Tax class.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'manage_stock'          => array(
					'description' => __( 'Stock management at product level.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'stock_quantity'        => array(
					'description' => __( 'Stock quantity.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'in_stock'              => array(
					'description' => __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'stock_status'          => array(
					'description' => __( 'Stock status.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'backorders'            => array(
					'description' => __( 'If managing stock, this controls if backorders are allowed.', 'superstore' ),
					'type'        => 'string',
					'default'     => 'no',
					'enum'        => array( 'no', 'notify', 'yes' ),
					'context'     => array( 'view', 'edit' ),
				),
				'backorders_allowed'    => array(
					'description' => __( 'Shows if backorders are allowed.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'backordered'           => array(
					'description' => __( 'Shows if the product is on backordered.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'sold_individually'     => array(
					'description' => __( 'Allow one item to be bought in a single order.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'weight'                => array(
					/* translators: %s: weight unit */
					'description' => sprintf( __( 'Product weight (%s).', 'superstore' ), $weight_unit ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'dimensions'            => array(
					'description' => __( 'Product dimensions.', 'superstore' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'length' => array(
							/* translators: %s: dimension unit */
							'description' => sprintf( __( 'Product length (%s).', 'superstore' ), $dimension_unit ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'width'  => array(
							/* translators: %s: dimension unit */
							'description' => sprintf( __( 'Product width (%s).', 'superstore' ), $dimension_unit ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'height' => array(
							/* translators: %s: dimension unit */
							'description' => sprintf( __( 'Product height (%s).', 'superstore' ), $dimension_unit ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'shipping_required'     => array(
					'description' => __( 'Shows if the product need to be shipped.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'shipping_taxable'      => array(
					'description' => __( 'Shows whether or not the product shipping is taxable.', 'superstore' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'shipping_class'        => array(
					'description' => __( 'Shipping class slug.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'shipping_class_id'     => array(
					'description' => __( 'Shipping class ID.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'reviews_allowed'       => array(
					'description' => __( 'Allow reviews.', 'superstore' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'average_rating'        => array(
					'description' => __( 'Reviews average rating.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'rating_count'          => array(
					'description' => __( 'Amount of reviews that the product have.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'related_ids'           => array(
					'description' => __( 'List of related products IDs.', 'superstore' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'upsell_ids'            => array(
					'description' => __( 'List of up-sell products IDs.', 'superstore' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
				),
				'cross_sell_ids'        => array(
					'description' => __( 'List of cross-sell products IDs.', 'superstore' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
				),
				'parent_id'             => array(
					'description' => __( 'Product parent ID.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'purchase_note'         => array(
					'description' => __( 'Optional note to send the customer after purchase.', 'superstore' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'categories'            => array(
					'description' => __( 'List of categories.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Category ID.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Category name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Category slug.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'tags'                  => array(
					'description' => __( 'List of tags.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Tag ID.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Tag name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Tag slug.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'thumbnail_image'       => array(
					'description' => __( 'Product thumbnail image.', 'superstore' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'id'                => array(
							'description' => __( 'Image ID.', 'superstore' ),
							'type'        => array( 'string', 'integer', 'boolean' ),
							'context'     => array( 'view', 'edit' ),
						),
						'date_created'      => array(
							'description' => __( "The date the image was created, in the site's timezone.", 'superstore' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_created_gmt'  => array(
							'description' => __( 'The date the image was created, as GMT.', 'superstore' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_modified'     => array(
							'description' => __( "The date the image was last modified, in the site's timezone.", 'superstore' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_modified_gmt' => array(
							'description' => __( 'The date the image was last modified, as GMT.', 'superstore' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'url'               => array(
							'description' => __( 'Image URL.', 'superstore' ),
							'type'        => array( 'string', 'boolean' ),
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
						'name'              => array(
							'description' => __( 'Image name.', 'superstore' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'alt'               => array(
							'description' => __( 'Image alternative text.', 'superstore' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'position'          => array(
							'description' => __( 'Image position. 0 means that the image is featured.', 'superstore' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'gallery_images'        => array(
					'description' => __( 'List of gallery images.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'                => array(
								'description' => __( 'Image ID.', 'superstore' ),
								'type'        => array( 'string', 'integer', 'boolean' ),
								'context'     => array( 'view', 'edit' ),
							),
							'date_created'      => array(
								'description' => __( "The date the image was created, in the site's timezone.", 'superstore' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_created_gmt'  => array(
								'description' => __( 'The date the image was created, as GMT.', 'superstore' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_modified'     => array(
								'description' => __( "The date the image was last modified, in the site's timezone.", 'superstore' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_modified_gmt' => array(
								'description' => __( 'The date the image was last modified, as GMT.', 'superstore' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'url'               => array(
								'description' => __( 'Image URL.', 'superstore' ),
								'type'        => array( 'string', 'boolean' ),
								'format'      => 'uri',
								'context'     => array( 'view', 'edit' ),
							),
							'name'              => array(
								'description' => __( 'Image name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'alt'               => array(
								'description' => __( 'Image alternative text.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'position'          => array(
								'description' => __( 'Image position. 0 means that the image is featured.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'attributes'            => array(
					'description' => __( 'List of attributes.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'        => array(
								'description' => __( 'Attribute ID.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name'      => array(
								'description' => __( 'Attribute name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'position'  => array(
								'description' => __( 'Attribute position.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'visible'   => array(
								'description' => __( "Define if the attribute is visible on the \"Additional information\" tab in the product's page.", 'superstore' ),
								'type'        => 'boolean',
								'default'     => false,
								'context'     => array( 'view', 'edit' ),
							),
							'variation' => array(
								'description' => __( 'Define if the attribute can be used as variation.', 'superstore' ),
								'type'        => 'boolean',
								'default'     => false,
								'context'     => array( 'view', 'edit' ),
							),
							'options'   => array(
								'description' => __( 'List of available term names of the attribute.', 'superstore' ),
								'type'        => 'array',
								'context'     => array( 'view', 'edit' ),
								'items'       => array(
									'type' => 'string',
								),
							),
						),
					),
				),
				'default_attributes'    => array(
					'description' => __( 'Defaults variation attributes.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'     => array(
								'description' => __( 'Attribute ID.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name'   => array(
								'description' => __( 'Attribute name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'option' => array(
								'description' => __( 'Selected attribute term name.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'variations'            => array(
					'description' => __( 'List of variations IDs.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'integer',
					),
					'readonly'    => true,
				),
				'grouped_products'      => array(
					'description' => __( 'List of grouped products ID.', 'superstore' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
				),
				'menu_order'            => array(
					'description' => __( 'Menu order, used to custom sort products.', 'superstore' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'             => array(
					'description' => __( 'Meta data.', 'superstore' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID.', 'superstore' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key.', 'superstore' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value.', 'superstore' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
