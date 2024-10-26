<?php
/* Template Name: Blog Lekérdezés */
get_header(); // Fejléc beillesztése

// Blog bejegyzések lekérdezése és megjelenítése a törlés funkcióval
function display_blog_posts() {
    $args = [
        'post_type' => 'blog',
        'posts_per_page' => -1,
    ];
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        echo '<div class="blog-posts">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            echo '<div class="blog-post">';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<p>Dátum: ' . get_the_date() . '</p>';
            echo '<button onclick="deletePost(' . $post_id . ')">Törlés</button>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>Nincs még blogbejegyzés.</p>';
    }
    wp_reset_postdata();
}

// A blogbejegyzések megjelenítése
display_blog_posts();

get_footer(); // Lábjegyzet beillesztése
?>

<!-- Inline JavaScript törlés funkcióhoz -->
<script>
function deletePost(postId) {
    if (confirm('Biztosan törölni szeretnéd ezt a bejegyzést?')) {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'delete_blog_post',
                'post_id': postId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('A bejegyzés törölve lett.');
                location.reload();
            } else {
                alert('Hiba történt a törlés során: ' + data.message);
            }
        });
    }
}
</script>
