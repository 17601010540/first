<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
    	echo 'hello ThinkPHP!';
    }

    public function test(){
    	$test = M('event');

    	$condition = [
    		'event.id' => '9427',
    	];

    	// var_dump($condition);
    	$result = $test->join('event_participants on event_participants.eventFK = event.id')->field('event_participants.id')->where($condition)->select();
    	printf($result);
    	var_dump($result);
    	echo $test->_sql();

    }

   public function total(){
   	// $where = [
   	// 	'participant.id' => '8156',
   	// ];
   	// $model = M('participant');
   	
   	// $result = $model->join('left join standing_participants on participant.id = standing_participants.participantFK')->field('standing_participants.id')->where($where)->select();
   	// $result1 = $result[0]['id'];
   	// $model1 = M('standing_data');
   	// $result2 = $model1->join('standing_type_param as param on param.id = standing_data.standing_type_paramFK')->field('param.name , standing_data.value')->where("standing_data.standing_participantsFK = $result1")->select();

   	// var_dump($result2);
   	// echo $model1->_sql();

   	$where = [
   		'standing.objectFK' => '2059407',
   	];
   	$data = M('standing');
   	$result = $data->join('standing_participants as part on part.standingFK = standing.id')->field('part.id')->where($where)->select();
   	// var_dump($result);
   	$where1 = [
   		'standing_participants.id' => '2600091',
   	];
   	$data1 = M('standing_participants');
   	$result1 = $data1->join('participant on participant.id = standing_participants.standing_participantsFK')->field('participant.name')->where($where1)->select();
   	var_dump($result1);
   	// $data1 = M('standing_data');

   	// var_dump($result1);
   }


   	public function first(){
   		//运动类型
   		$sport = M('sport');
   		$result = $sport->select();
   		// var_dump($result);
   		$sportID = $result[0]['id'];//足球的ID
   		//联赛表
   		$template = M('tournament_template');
   		$result1 = $template->field('id , name , gender')->where("sportFK = $sportID")->find();
   		// var_dump($result1);
   		//Austria 1联赛下面的所有赛季

   		$tourID = $result1['id'];
   		$tour = M('tournament');
   		$result2 = $tour->field('id , name')->where("tournament_templateFK = $tourID")->select();
   		// var_dump($result2);

   		//2001/2002赛季下面的所有赛段和国家地区
   		$stageID = $result2[0]['id'];
   		$stage = M('tournament_stage');
   		$result3 = $stage->join('country on country.id = tournament_stage.countryfk')->field('country.name , tournament_stage.id')->where("tournamentFK = $stageID")->find();
   		// var_dump($result3);

   		//tipp3-Bundesliga赛段和该赛段的赛程
   		$eventID = $result3['id'];
   		$event = M('event');
   		$result4 = $event->where("tournament_stageFK = $eventID")->select();
   		// var_dump($result4);

   		//假设获取的event的id = 9427;
   		$epartID = '9427';
   		$epart = M('event_participants');
   		$result5 = $epart->join('participant on participant.id = event_participants.participantFK')->field('participant.id , number , participant.name , type')->where("eventFK = $epartID")->find();
   		// var_dump($result5);

   		//已知球员的ID = 204683;
   		$objectID = '204683';
   		$object = M('object_participants');
   		$result6 = $object->where("participantFK = $objectID")->select();
   		// var_dump($result6);


   		//多表联查
   		$where = [
   			'sport.id' => 1, 
   		];
   		$model = M('sport');
   		$res = $model->join('tournament_template as template on template.sportFK = sport.id')
      					->join('tournament on tournament.tournament_templateFK = template.id')
      					->join('tournament_stage as stage on stage.tournamentFK = tournament.id')
      					->join('country on country.id = stage.countryFK')
      					->join('event on event.tournament_stageFK = stage.id')
      					->join('event_participants as epart on epart.eventFK = event.id')
      					->join('participant on epart.participantFK = participant.id')
      					->join("object_participants as object on object.object = 'participant' and object.participantFK = participant.id")
      					->where($where)->select();
   		// var_dump($res);
   		// echo $model->_sql();
   		print_r($res);


   		
   	}
   	//查询参赛记录表中某一球队的球员的具体信息
   	public function second()
   	{
   		
   		$where2 = [
   			'event_participants.eventFK' => 2866906,
   			'object_participants.object' => 'participant',
   			'property.object'            => 'participant',
   		];
   		$model = M('event_participants');
   		$res = $model->join('object_participants on object_participants.objectFK = event_participants.participantFK')
   					->join('right join property on property.objectFK = object_participants.participantFK')
   					->join('participant on participant.id = object_participants.participantFK')
   					->join('lineup on lineup.participantFK = object_participants.participantFK')
   					->join('lineup_type on lineup_type.id = lineup.lineup_typeFK')
   					->field('participant.name as participant_name , lineup_type.name as lineup_type_name , property.*')->where($where2)->select();
   		// var_dump($res);
   		print_r($res);
   		echo $model->_sql();
   	}

   	//比赛结果
   	public function third(){

   		$where3 = [
   			'event_participants.eventFK' => '2181081',

   		];
   		$demo = M('event_participants');
   		$res = $demo->join('result on result.event_participantsFK = event_participants.id')
   					->join('result_type on result_type.id = result.result_typeFK')
   					->join("language on language.objectFK = result_type.id and language.object = 'result_type'")
   					->field('result.id , language.name , event_participants.id')->where($where3)->select();

   		// var_dump($res);
   		echo $demo->_sql();
   	}

   	//发生事件
   	public function next(){
   		$where4 = [
   			'event_participants.eventFK' => '2581537',
   		];
   		$demo = M('event_participants');
   		$res =$demo->join('incident on incident.event_participantsFK = event_participants.id')
   					->join('incident_type on incident_type.id = incident.incident_typeFK')
   					->join("language on language.objectFK = incident_type.id and language.object = 'incident_type'")
   					->join('participant on participant.id = event_participants.participantFK')
   					->field('participant.name as pname , language.name as lname')->where($where4)->select();
   		print_r($res);
   		echo $demo->_sql();
   	}


   	//查询某一赛段下面的某个球队的积分数据
   	public function standing(){
   		$where = [
   			'tournament_stage.tournamentFK' => '11943',
   		];
   		$demo = M('tournament_stage');
   		$res = $demo->join("standing on standing.objectFK = tournament_stage.id and standing.object = 'tournament_stage'")
   					->join('standing_type on standing_type.id = standing.standing_typeFK')
   					->join('standing_participants on standing_participants.standingFK = standing.id')
   					->join('participant on participant.id = standing_participants.participantFK')
   					->join('standing_data on standing_data.standing_participantsFK = standing_participants.id')
   					->join('standing_type_param on standing_type_param.id = standing_data.standing_type_paramFK and standing_type_param.standing_typeFK = standing_type.id')
   					->field('participant.name as pname , standing_type_param.name as stpname , standing_data.value as sdvalue')->where($where)->select();

   		print_r($res);
   		echo $demo->_Sql();

   	}


   	//查询某一赛段下的某一球队的各项数据统计
   	public function statistic(){
   		$where = [
   			'statistic.object_typeFK'     => 4,
   			'statistic.objectFK'          => 855513,
   			'statistic.statistic_typeFK'  => 5,
   		];
   		$demo = M('statistic');
   		$res = $demo->join('object_type on object_type.id = statistic.object_typeFK')
   					->join('tournament_stage as tstage on tstage.id = statistic.objectFK')
   					->join('statistic_type as stype on stype.id = statistic.statistic_typeFK')
   					->join('statistic_participants5 as spart5 on spart5.statisticFK = statistic.id')
   					->join('participant on participant.id = spart5.participantFK')
   					->join('statistic_data5 as sdata5 on sdata5.statistic_participants5FK = spart5.id')
   					->join('statistic_data_type as sdtype on sdtype.id = sdata5.statistic_data_typeFK')
   					->field('tstage.name as tstage_name , stype.name as stype_name , participant.name as pname , sdtype.name as sdtype_name , sdata5.value')->where($where)->select();

   		// var_dump($res);
   		// echo $demo->_sql();
   					print_r($res);
   		
   	}


   	//文字直播
   	public function live(){
   		$where = [
   			'event_incident.eventFK' => 2763966,
   		];
   		$model = M('event_incident');
   		$res = $model->join('event_incident_detail as eidetail on eidetail.event_incidentFK = event_incident.id')
   					->join('participant on participant.id = eidetail.participantFK')
   					->join('event on event.id = event_incident.eventFK')
   					->field('event.name as ename , participant.name as pname , eidetail.type , eidetail.value')->where($where)->select();

   		print_r($res);

   	}

   	//[赔率]
   	public function odds(){
   		$where = [
   			'outcome.objectFK' => 2684710,
   			'outcome.object'   => 'event',
   			'language.object'  => 'country',
   		];
   		$demo = M('outcome');
   		$res = $demo->join('event on event.id = outcome.objectFK')
   					->join('bettingoffer on bettingoffer.outcomeFK = outcome.id')
   					->join('odds_provider on odds_provider.id = bettingoffer.odds_providerFK')
   					->join('country on country.id = odds_provider.countryFK')
   					->join("language on language.objectFK = country.id")
   					->field('odds_provider.name as odds_pname , odds_provider.url , event.name as ename , outcome.type , language.name as lname')->where($where)->select();

   		// var_dump($res);
   		print_r($res);
   		echo $demo->_sql();
   	}
                                       

   	public function standings(){

   		$where = [
   			'standing.objectFK' => '854008',
   			'standing.object'   => 'tournament_stage',
   			'language.object'   => 'standing_type',
   		];

   		$demo = M('standing');

   		$res = $demo->join('standing_type as stype on stype.id = standing.standing_typeFK')
   						->join('standing_participants on standing_participants.standingFK = standing.id')
   						->join('participant on participant.id = standing_participants.participantFK')
   						->join('standing_data on standing_data.standing_participantsFK = standing_participants.id')
   						->join('language on language.objectFK = stype.id')
   						->where($where)->find();

   		var_dump($res);
   						print_r($res);
   		echo $demo->_sql();
   	}

}
