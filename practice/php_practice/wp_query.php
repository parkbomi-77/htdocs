wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/lesson.php



https://gipyeonglee.tistory.com/24

    
wp쿼리 

$q 속성 
$query
$wp_queryWP 클래스 에서 개체에 전달한 쿼리 문자열을 보유합니다 .
$query_vars
dissected를 포함하는 연관 배열 $query: 쿼리 변수 및 해당 값의 배열.
$queried_object
요청이 카테고리, 작성자, 영구 링크 또는 페이지인 경우에 적용됩니다. 요청된 범주, 작성자, 게시물 또는 페이지에 대한 정보를 보유합니다.
$queried_object_id
요청이 카테고리, 작성자, 퍼머링크 또는 게시물/페이지인 경우 해당 ID를 보유합니다.
$posts
데이터베이스에서 요청한 게시물로 채워집니다.
$post_count
표시되는 게시물의 수입니다.
$found_posts
현재 쿼리 매개변수와 일치하는 총 게시물 수
$max_num_pages
총 페이지 수입니다. $found_posts / $posts_per_page의 결과입니다.
$current_post
(The Loop 중에 사용 가능) 현재 표시되는 게시물의 인덱스입니다.
$post
(The Loop에서 사용 가능) 현재 표시되는 게시물입니다.
$is_single, $is_page, $is_archive, $is_preview, $is_date, $is_year, $is_month, $is_time, , $is_author, $is_category, $is_tag, $is_tax, $is_search, $is_feed, $is_comment_feed, $is_trackback, $is_home, $is_404, $is_comments_popup, $is_admin, $is_attachment, $is_singular, $is_robots요청 유형 $is_posts_page을 $is_paged
나타내는 부울. 예를 들어 처음 세 개는 각각 '영구 링크입니까?', '페이지입니까?', '아무 유형의 아카이브 페이지입니까?'를 나타냅니다. 조건부 태그 를 참조하십시오 .




// post의 특정 값 가져오기 
$q = new WP_Query(array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-lessons',
		'post__in'       => array($item_id)
	));
var_dump($q->post->post_modified); 

// 행의 갯수 출력
global $wpdb;
$row = $wpdb->query( "SELECT * FROM vetschool.wp_posts");
var_dump($row); 

// 오브젝트로 가져오기 
global $wpdb;
	$results = $wpdb->get_results( 'SELECT * FROM wp_posts where ID = 10578', OBJECT );	
	var_dump($results);


// 업데이트하기. 업데이트된 행수 리턴
global $wpdb;
$results = $wpdb->update($wpdb->posts,array("post_title"=>"22"),array("ID"=>$item_id));
var_dump($results);

edit-form-advanced.php
lesson 등록하는 페이지 


/Applications/MAMP/htdocs/wp-admin/includes/upgrade.php
$wpdb->insert(
			$wpdb->posts,
			array(
				'post_author'           => $user_id,
				'post_date'             => $now,
				'post_date_gmt'         => $now_gmt,
				'post_content'          => $first_post,
				'post_excerpt'          => '',
				'post_title'            => __( 'Hello world!' ),
				/* translators: Default post slug. */
				'post_name'             => sanitize_title( _x( 'hello-world', 'Default post slug' ) ),
				'post_modified'         => $now,
				'post_modified_gmt'     => $now_gmt,
				'guid'                  => $first_post_guid,
				'comment_count'         => 1,
				'to_ping'               => '',
				'pinged'                => '',
				'post_content_filtered' => '',
			)
		);


wp_comments 처럼 .. 
CREATE TABLE `vetschool`.`wp_play_time` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `posts_lesson_id` INT NOT NULL,
  `product_time` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `ID_UNIQUE` (`ID` ASC));




  <!-- // require 'vendor/autoload.php';
require "/Applications/MAMP/htdocs/wp-content/plugins/masterstudy-lms-learning-management-system-pro/addons/google_classrooms/vendor/autoload.php";

use Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

// 로거 채널 생성
$log = new Logger('name');

// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$log->pushHandler(new StreamHandler('/Applications/MAMP/htdocs/wp-content/uploads/wc-logs/php.log', 
Logger::DEBUG, true));

// add records to the log
$log->info('API Request Started.', array('path' => $_REQUEST['_url']));

// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
 $log->CRITICAL('Debug log');

// Error 와 warnig 은 출력
$log->ALERT('Error log');
$log->EMERGENCY('Warning log'); -->