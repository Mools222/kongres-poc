<?php
require_once get_stylesheet_directory() . '/vendor/autoload.php';

class DatabaseSeeder {
    private $faker;

    public function __construct() {
        $this->faker = Faker\Factory::create('da_DK');
    }

    public function seed_users_randomly($number_of_users) {
        $user_ids = [];

        for ($i = 0; $i < $number_of_users; $i++) {
            $user_name = $this->faker->userName;
            $email = $this->faker->email;

            if (username_exists($user_name) || email_exists($email)) {
                $i--;
            } else {
                $user_id = $this->create_user($user_name, 'test', $email, $this->faker->firstName, $this->faker->lastName);
                $user_ids[] = $user_id;
            }
        }

        return $user_ids;
    }

    public function create_user($user_name, $password, $email, $first_name, $last_name) {
        $user_id = wp_create_user($user_name, $password, $email); // Default role seems to be subscriber

        wp_update_user([
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ]);

        return $user_id;
    }

    public function seed_events_randomly($arrangement_id, $number_of_events, $user_ids) {
        $event_ids = [];

        for ($i = 0; $i < $number_of_events; $i++) {
            $event_name = $this->faker->words(3, true);
            $event_name = ucfirst($event_name);

            $datetime_start = $this->faker->dateTimeBetween('now', '2 weeks');
            $event_start_datetime = $datetime_start->format("Y-m-d H:i:s");

            $event_duration_hours = $this->faker->numberBetween(1, 5);
            $datetime_end = $datetime_start->add(new DateInterval("PT{$event_duration_hours}H"));
            $event_end_datetime = $datetime_end->format("Y-m-d H:i:s");

            $about_string = $this->faker->paragraphs(3, true);
            $arrangement_string = $this->faker->paragraphs(3, true);
            $summary_string = $this->faker->paragraphs(3, true);

            $event_id = $this->create_event($event_name, $this->faker->address, $event_start_datetime,
                $event_end_datetime, $arrangement_id, null, false,
                10, $user_ids, $about_string, $arrangement_string, $summary_string);

            $event_ids[] = $event_id;
        }

        return $event_ids;
    }


    public function create_event($event_name, $event_location, $event_start_datetime, $event_end_datetime,
                                 $arrangement_id, $event_sponsor_id = null, $mandatory_registration = false,
                                 $event_max_registered_users = 10, $user_ids = null, $about_text = '',
                                 $arrangement_text = '', $summary_text = '') {
        $event_id = wp_insert_post(array(
            'post_type' => 'event',
            'post_title' => $event_name,
            'post_status' => 'publish',
        ));

        update_field('event_name', $event_name, $event_id);
        update_field('event_location', $event_location, $event_id);
        update_field('event_start_datetime', $event_start_datetime, $event_id);
        update_field('event_end_datetime', $event_end_datetime, $event_id);
        update_field('event_arrangement', $arrangement_id, $event_id);

        if ($event_sponsor_id)
            update_field('event_sponsor', $event_sponsor_id, $event_id);

        if ($mandatory_registration)
            update_field('event_mandatory_registration', 1, $event_id);

        update_field('event_max_registered_users', $event_max_registered_users, $event_id);

        if ($user_ids)
            update_field('event_registered_users', $user_ids, $event_id);

        update_field('event_about_tab', $about_text, $event_id);
        update_field('event_arrangement_tab', $arrangement_text, $event_id);
        update_field('event_summary_tab', $summary_text, $event_id);

        return $event_id;
    }
}