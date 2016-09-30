<?php defined('BASEPATH') OR exit('No direct script access allowed');

define("JEDOX_ETLPORT", "7775");

class Etlapi {
	
	//public $id = '';
	private $ci;
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
	}
	
	function GetProjects()
	{
		//$server = new SoapClient("http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl", array('exceptions' => true) );
		$server = new SoapClient(testurl(), array('exceptions' => true) );
		$res = $server->getNames();
		if(count($res->return) == 0) {
		return "";
		}
		return $res->return;
	}
	
	function GetJobs($job)
	{
		if(empty($job)) return "";
		//$server = new SoapClient("http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl", array('exceptions' => true) );
		$server = new SoapClient(testurl(), array('exceptions' => true) );
		$res = $server->getNames(array('locator' => $job.'.jobs'));
		if(!$res->return) return "";  
		return $res->return;
	}
	
	function Start_Job($task_type = '', $pject = '', $pjob = '', $cvars = '', $sourceCon = '', $targetCon = '', $sourceDB = '', $targetDB = '', $sourceCube = '', $targetCube = '') // how to set correct job?
	{
		try
		{
			//$s = activesheet();// ? source. sheet is the page that controls or holds all data set...
			$s = $task_type;
			//$server = @new SoapClient("http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl", array('exceptions' => true) );
			$server = @new SoapClient(testurl(), array('exceptions' => true) );

			$project = "ETLTasks";
			$type = "jobs";
			//$name = $s->name;
			$name = $task_type;

			if($name == "Execute ETL Job")
			{
				$project = $pject; // Project
				$name = $pjob; // Job
			}

			if(empty($name) || empty($project)) return;

			// Variables... where will these be
			//$sourceCon = $s->range("D10")->value;
			//$targetCon = $s->range("F10")->value;
			//$sourceDB = $s->range("D11")->value;
			//$targetDB = $s->range("F11")->value;
			//$sourceCube = $s->range("D12")->value;
			//$targetCube = $s->range("F12")->value;

			$locator = "$project.$type.$name";
			$variables = "";

			// Set variables
			if($name == "Execute ETL Job"){
				$variables = $cvars;
			}
			if($name == "CubeCopy" || $name == "CubeRulesCalc" || $name == "CubeAnonymize")
			{
				$variables = array(array("name" => "TargetCube", "value" => $targetCube), array("name" => "Cube", "value" => $sourceCube), array("name" => "PaloTargetDB", "value" => $targetDB), array("name" => "PaloSourceDB", "value" => $sourceDB), array("name" => "PaloSourceConn", "value" => $sourceCon), array("name" => "PaloTargetConn", "value" => $targetCon));
			}

			if($name == "DatabaseBackup")
			{
				$variables = array(array("name" => "PaloSourceDB", "value" => $sourceDB), array("name" => "PaloSourceConn", "value" => $sourceCon), array("name" => "FilePath", "value" => $sourceCube));
			}

			if($name == "DatabaseRestore")
			{
				$variables = array(array("name" => "PaloTargetDB", "value" => $sourceDB), array("name" => "PaloTargetConn", "value" => $sourceCon), array("name" => "FilePath", "value" => $sourceCube));
			}

			if($name == "DatabaseCopy")
			{
				$variables = array(array("name" => "PaloTargetDB", "value" => $targetDB), array("name" => "PaloSourceDB", "value" => $sourceDB), array("name" => "PaloSourceConn", "value" => $sourceCon), array("name" => "PaloTargetConn", "value" => $targetCon));
			}

			// Check wether the job is already queued
			$p = array("project" => $project, "type" => $type, "name" => $name, "after" => doubleval(0), "before" => doubleval(0), "status" => "0");
			$response = $server->getExecutionHistory($p);
			//print_r($response);
			//$return = $response->return; //what does this return or test?

			if(isset($response->return) && count($response->return) > 0) {
				//$s->range("D16")->value = "Job is already queued!"; // returns error message.
				$edata = "Job is already queued!"; // returns error message.
				return $edata;
			}
			else
			{
				// Check wether job is already running
				$p = array("project" => $project, "type" => $type, "name" => $name, "after" => doubleval(0), "before" => doubleval(0), "status" => "5");
				$response = $server->getExecutionHistory($p);
				//$return = $response->return;

				if(isset($response->return) && count($response->return) > 0) {
					$s->range("D16")->value = "Job is running!"; // returns error message.
					$edata = "Job is running!"; // returns error message.
					return $edata;
				}
				else
				{
					// Everything ok - add and start job
					$response = $server->addExecution(array('locator' => $locator, 'variables' => $variables));
					$return = $response->return;

					//  Check wether there was an error on job initialisation
					$valid = $return->valid;
					if (!$valid)  {
						//$s->range("D16")->value = $return->errorMessage; // returns error message.
						$edata = $return->errorMessage;
						return $edata;
					}
					$id = $return->id; // why is this value not reflected but can be used normally??? seems that this value is actually written in a hidden field.
					$this->ci->session->set_userdata('etlid', $id);
					//var_dump($return);
					//  Execute job
					$response = $server->runExecution(array('id' => $id));
					//$return = $response->return;
					$return = $response->return;
					//var_dump($return);
					// Check wether there was an error on execution
					//$p = array("project" => $project, "type" => $type, "name" => $name, "after" => doubleval(0), "before" => doubleval(0), "status" => "40");
					//$response = $server->getExecutionHistory($p);
					//$return = $response->return;

					//$s->range("B15")->value = $id; //hidden value for processing?
					//$edata = $this->getStatus($id);
					return $return->status;
				}
			}
		}
		catch (SoapFault $fault)
		{
			//$s->range("D16")->value = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
			$edata = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
			return $edata;
		}
			catch (Exception $e)
		{
			//$s->range("D16")->value = $e->getMessage();
			$edata = $e->getMessage();
			return $edata;
		}  
	}
	function getStatus($id = '')
	{
		//$s = activesheet();
		if($id == ''){
			$id = $this->ci->session->userdata('etlid');
		}
		//$server = @new SoapClient("http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl", array('exceptions' => true) );  
		$server = @new SoapClient(testurl(), array('exceptions' => true) );  
		//$id = $s->range("B15")->value; // getting hidden value to check status?
  
		// Am ende den Status holen und in die Zelle schreiben
		$response = $server->getExecutionStatus(array('id' => $id, 'waitForTermination' => false));
		$return = $response->return;
  
		//$s->range("D16")->value = $return->status; // return status to field??
		$edata = $return->status;
		return $edata;
	}
	
	function displayFunctions(){
		//$server = new SoapClient("http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl", array('exceptions' => true) );
		$server = new SoapClient(testurl(), array('exceptions' => true) );
		var_dump($server->__getFunctions());
	}
	
	function testurl()
	{
		$server_url = ''; // initialize variable
		if(base_url() == "http://demo.proeo.com/")
		{
			$server_url = 'http://127.0.0.1:7775/etlserver/services/ETL-Server?wsdl'; // specific to demo server
		} else {
			$server_url = rtrim(base_url(), "/").':7775/etlserver/services/ETL-Server?wsdl';
		}
		return $server_url;
	}
}
