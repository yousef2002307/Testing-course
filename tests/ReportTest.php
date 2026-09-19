<?php

use PHPUnit\Framework\TestCase;
use App\Report\LegacyReportService;

class ReportTest extends TestCase
{
    public function test_process_data()
    {
        //arrange
        $user = [
            'name' => 'Ahmed',
            'active' => true,
            'tier' => 'REGULAR',
            'country' => 'EG',
            'member_year' => 2022
        ];
        $items = [
            ['price' => 100, 'qty' => 2], // 200
        ];

        $reportclass = new LegacyReportService();

        //act
        $result = $reportclass->processData($user, $items);

        //assert
        $expected = [
            'status' => 'SUCCESS',
            'sub_total' => 200.0,
            'disc_amt' => 0.0,
            'tax' => 28.0,
            'shipping' => 10.0,
            'grand_total' => 238.0,
            'points' => 20
        ];
        $this->assertEquals($expected, $result);
    }
}
