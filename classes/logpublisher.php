<?php
require_once("PangeaAmqp.class.php");
class LogPublisher
{
    public $amqpConnection;
    /**
     * @param $couchdb_details contain all the couch conection details
     * @param $amqp_details contails all the rabbitmq details
     * @param $logs_path contains the path of the logs folder
     * @return FacebookMessagePublisher instance
     */
    public static function getInstance($amqp_details)
    {
        static $obj = null;
        if ($obj === null) {
            try{
                $obj = new LogPublisher($amqp_details);
            }
            catch(Exception $e)
            {
               throw new Exception($e->getMessage()); 
            }
        }
        return $obj;            
    }

    /**
     * @param $couchdb_details contain all the couch conection details
     * @param $amqp_details contails all the rabbitmq details
     * @param $logs_path contains the path of the logs folder
     * @return FacebookMessagePublisher instance
     */
    private function __construct($amqp_details)
    {
        try{
                //getting the rabbitMQ connection
                $this->amqpConnection = new PangeaAmqp($amqp_details['host'], 
                                $amqp_details['port'], 
                                $amqp_details['username'], 
                                $amqp_details['password']);
        }
        catch(Exception $e){
            throw new Exception("rabbitmq_connection_failure");
        }
        
    }

    /**
     * this is publish message function.. this perfomrs the main functionality
     * @param $message_details is an message
     * @return unique token 
     */
    public function publishMessage($message_details)
    {
        if($this->insertMessageToQueue("pangea", "test_new", $message_details))
        {
            return "yes";           
        }   
        else
            throw new Exception("queue_message_insert_faliure");

    }

    /**
     * this function inserts the message in to queue
     * @param $exchangeName is the name of the exchange to be declared
     * @param $account_id is the account id used for creating the queue
     * @param $message is the json object that contain data and token
     * @return boolen 
     */
    private function insertMessageToQueue($exchangeName, $account_id, $message)
    {
        //performing the rabbitmq operations
        $this->amqpConnection -> declareExchange($exchangeName);
        $res = $this->amqpConnection -> declareQueue($account_id);
        $this->amqpConnection -> bindExchange($account_id);
        $this->amqpConnection -> insertMessage($message);
        return true;
    }
}
?>
