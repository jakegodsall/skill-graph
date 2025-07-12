<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityDependency;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillGraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo user if it doesn't exist
        $user = User::firstOrCreate([
            'email' => 'demo@example.com'
        ], [
            'name' => 'Demo User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create skills
        $pythonSkill = Skill::firstOrCreate([
            'name' => 'Python',
            'user_id' => $user->id,
        ], [
            'description' => 'Python programming language and ecosystem',
            'color' => '#3776ab',
        ]);

        $webDevSkill = Skill::firstOrCreate([
            'name' => 'Web Development',
            'user_id' => $user->id,
        ], [
            'description' => 'Full-stack web development skills',
            'color' => '#f7931e',
        ]);

        $dataSkill = Skill::firstOrCreate([
            'name' => 'Data Science',
            'user_id' => $user->id,
        ], [
            'description' => 'Data analysis and machine learning',
            'color' => '#e97627',
        ]);

        // Create Python activities
        $pythonBasics = Activity::firstOrCreate([
            'name' => 'Python Basics Course',
            'user_id' => $user->id,
            'skill_id' => $pythonSkill->id,
        ], [
            'description' => 'Learn Python fundamentals - variables, loops, functions',
            'type' => 'course',
            'status' => 'completed',
            'url' => 'https://python.org/tutorial',
            'estimated_hours' => 40,
            'actual_hours' => 35,
            'position_x' => 100,
            'position_y' => 50,
        ]);

        $pythonOOP = Activity::firstOrCreate([
            'name' => 'Python OOP',
            'user_id' => $user->id,
            'skill_id' => $pythonSkill->id,
        ], [
            'description' => 'Object-oriented programming in Python',
            'type' => 'course',
            'status' => 'completed',
            'estimated_hours' => 25,
            'actual_hours' => 30,
            'position_x' => 300,
            'position_y' => 50,
        ]);

        $pythonStandardLib = Activity::firstOrCreate([
            'name' => 'Python Standard Library',
            'user_id' => $user->id,
            'skill_id' => $pythonSkill->id,
        ], [
            'description' => 'Explore Python\'s built-in modules and libraries',
            'type' => 'practice',
            'status' => 'in_progress',
            'estimated_hours' => 20,
            'position_x' => 500,
            'position_y' => 50,
        ]);

        $wcClone = Activity::firstOrCreate([
            'name' => 'Build WC Clone',
            'user_id' => $user->id,
            'skill_id' => $pythonSkill->id,
        ], [
            'description' => 'Recreate the Unix wc command line tool in Python',
            'type' => 'project',
            'status' => 'completed',
            'url' => 'https://github.com/example/wc-clone',
            'estimated_hours' => 15,
            'actual_hours' => 18,
            'position_x' => 200,
            'position_y' => 200,
        ]);

        $exercismPython = Activity::firstOrCreate([
            'name' => 'Exercism Python Track',
            'user_id' => $user->id,
            'skill_id' => $pythonSkill->id,
        ], [
            'description' => 'Complete coding exercises on Exercism',
            'type' => 'practice',
            'status' => 'in_progress',
            'url' => 'https://exercism.org/tracks/python',
            'estimated_hours' => 50,
            'actual_hours' => 30,
            'position_x' => 400,
            'position_y' => 200,
        ]);

        $pythonWeb = Activity::firstOrCreate([
            'name' => 'Python Web Framework',
            'user_id' => $user->id,
            'skill_id' => $webDevSkill->id,
        ], [
            'description' => 'Learn Flask or Django for web development',
            'type' => 'course',
            'status' => 'not_started',
            'estimated_hours' => 60,
            'position_x' => 600,
            'position_y' => 200,
        ]);

        // Create Web Development activities
        $htmlCss = Activity::firstOrCreate([
            'name' => 'HTML & CSS Fundamentals',
            'user_id' => $user->id,
            'skill_id' => $webDevSkill->id,
        ], [
            'description' => 'Learn HTML structure and CSS styling',
            'type' => 'course',
            'status' => 'completed',
            'estimated_hours' => 30,
            'actual_hours' => 25,
            'position_x' => 100,
            'position_y' => 350,
        ]);

        $javascript = Activity::firstOrCreate([
            'name' => 'JavaScript Essentials',
            'user_id' => $user->id,
            'skill_id' => $webDevSkill->id,
        ], [
            'description' => 'Master JavaScript fundamentals',
            'type' => 'course',
            'status' => 'completed',
            'estimated_hours' => 40,
            'actual_hours' => 45,
            'position_x' => 300,
            'position_y' => 350,
        ]);

        $portfolio = Activity::firstOrCreate([
            'name' => 'Portfolio Website',
            'user_id' => $user->id,
            'skill_id' => $webDevSkill->id,
        ], [
            'description' => 'Build a personal portfolio website',
            'type' => 'project',
            'status' => 'completed',
            'url' => 'https://github.com/example/portfolio',
            'estimated_hours' => 25,
            'actual_hours' => 35,
            'position_x' => 500,
            'position_y' => 350,
        ]);

        // Create Data Science activities
        $pandasBook = Activity::firstOrCreate([
            'name' => 'Pandas for Data Analysis',
            'user_id' => $user->id,
            'skill_id' => $dataSkill->id,
        ], [
            'description' => 'Learn data manipulation with Pandas',
            'type' => 'book',
            'status' => 'in_progress',
            'estimated_hours' => 40,
            'position_x' => 200,
            'position_y' => 500,
        ]);

        $mlCourse = Activity::firstOrCreate([
            'name' => 'Machine Learning Course',
            'user_id' => $user->id,
            'skill_id' => $dataSkill->id,
        ], [
            'description' => 'Introduction to machine learning concepts',
            'type' => 'course',
            'status' => 'not_started',
            'estimated_hours' => 80,
            'position_x' => 400,
            'position_y' => 500,
        ]);

        $dataProject = Activity::firstOrCreate([
            'name' => 'Data Analysis Project',
            'user_id' => $user->id,
            'skill_id' => $dataSkill->id,
        ], [
            'description' => 'Analyze real-world dataset and present findings',
            'type' => 'project',
            'status' => 'not_started',
            'estimated_hours' => 50,
            'position_x' => 600,
            'position_y' => 500,
        ]);

        // Create dependencies
        ActivityDependency::firstOrCreate([
            'activity_id' => $pythonOOP->id,
            'depends_on_activity_id' => $pythonBasics->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $pythonStandardLib->id,
            'depends_on_activity_id' => $pythonOOP->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $wcClone->id,
            'depends_on_activity_id' => $pythonBasics->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $exercismPython->id,
            'depends_on_activity_id' => $pythonBasics->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $pythonWeb->id,
            'depends_on_activity_id' => $pythonOOP->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $portfolio->id,
            'depends_on_activity_id' => $htmlCss->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $portfolio->id,
            'depends_on_activity_id' => $javascript->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $pandasBook->id,
            'depends_on_activity_id' => $pythonBasics->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $mlCourse->id,
            'depends_on_activity_id' => $pandasBook->id,
        ]);

        ActivityDependency::firstOrCreate([
            'activity_id' => $dataProject->id,
            'depends_on_activity_id' => $mlCourse->id,
        ]);

        $this->command->info('Skill graph seeder completed successfully!');
    }
}
