<?php

use App\Services\Notification\NotificationService;

class NotificationTest extends TestCase {

    /**
     * Test setting "Success Notification"
     */
    public function testSuccessNotification()
    {
        $notification = new NotificationService();

        $text = "Test was succesful";
        $notification->success($text);

        $output = $notification->display();

        // has class name
        $this->assertContains("alert-success", $output);

        // contains message
        $this->assertContains($text, $output);
    }

    /**
     * Test setting "Error Notification"
     */
    public function testErrorNotification()
    {
        $notification = new NotificationService();

        $text = "Test was not succesful";
        $notification->error($text);

        $output = $notification->display();

        // has class name
        $this->assertContains("alert-danger", $output);

        // contains message
        $this->assertContains($text, $output);
    }

    /**
     * Test setting a message
     */
    public function testSettingMessage()
    {
        $text = 'This is an success message.';
        $type = 'success';

        $notification = new NotificationService();
        $notification->set($text, $type);

        $this->assertTrue(\Session::has('message'));

        $message = \Session::get('message');

        // contains message
        $this->assertEquals($type, $message['type']);
        $this->assertEquals($text, $message['message']);
    }


}
