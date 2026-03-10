<?php
/**
 * Plugin Name: NExT Hide Private Post Prefix
 * Plugin URI:  https://github.com/
 * Description: ログインユーザーに対しても、非公開記事を投稿一覧から除外します。
 * Version:           1.1.0
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Author:            NExT-Season
 * Author URI:        https://next-season.net
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nhpp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ログインユーザーに対しても非公開記事をフロントエンドのすべてのクエリから除外する。
 *
 * WordPress のデフォルト動作ではログインユーザーに非公開記事が表示されるが、
 * このフックにより未ログインユーザーと同じ挙動に統一する。
 * メインループだけでなく、Query Loop ブロック・Advanced Query Loop 等の
 * セカンダリクエリにも適用される。
 * 管理画面・REST API は対象外。
 *
 * @param WP_Query $query 処理中のクエリオブジェクト
 */
function nhpp_exclude_private_posts_from_loop( WP_Query $query ): void {
	// 管理画面・REST API は対象外
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	// プレビュー・単一記事は対象外
	// （下書きプレビューは post_status が空のまま WordPress が内部処理するため、
	// ここで 'publish' に上書きすると draft が見つからなくなる）
	if ( $query->is_preview() || $query->is_singular() ) {
		return;
	}

	// ログインしていない場合は WordPress のデフォルト挙動に任せる
	// （未ログインでは private 記事はもともと表示されない）
	if ( ! is_user_logged_in() ) {
		return;
	}

	// 'private' ステータスを post_status から除外する
	$post_status = $query->get( 'post_status' );

	if ( empty( $post_status ) ) {
		$post_status = array( 'publish' );
	} elseif ( is_string( $post_status ) ) {
		$post_status = explode( ',', $post_status );
	}

	$post_status = array_map( 'trim', (array) $post_status );
	$post_status = array_filter( $post_status, fn( $s ) => $s !== 'private' );

	// 'any' が含まれている場合は明示的に private を除いたリストに展開
	if ( in_array( 'any', $post_status, true ) ) {
		$public_statuses = get_post_stati( array( 'public' => true ) );
		unset( $public_statuses['private'] );
		$post_status = array_values( $public_statuses );
	}

	if ( empty( $post_status ) ) {
		$post_status = array( 'publish' );
	}

	$query->set( 'post_status', array_values( $post_status ) );
}
add_action( 'pre_get_posts', 'nhpp_exclude_private_posts_from_loop' );
