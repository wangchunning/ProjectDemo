<?php

class SwiftCodeSeeder extends Seeder {

    public function run()
    {
        $file = file_get_contents(app_path() . '/database/seeds/swift_code.sql');
        
        // 解析 SQL 语句
        $file_array = array_filter(explode(';', $file));

        // 运行 SQL
        foreach($file_array as $query)
        {
            if ( ! trim($query)) continue;
            DB::insert($query);
        }
    }
}