<?php
class Class_CoupangAPI {
    
    public  $args    =   array();
    
    public $baseurl     =   'https://api-gateway.coupang.com';
    public $accessKey   =   '';
    public $secretKey   =   '';
    public $vendorId    =   '';
    public $datetime    =   '';
    
    
    public function __construct($args)
    {
        $this->args       =   $args;
        $this->datetime		=   date("ymd").'T'.date("His").'Z';
        $this->accessKey  =   $this->args['accessKey'];
        $this->secretKey  =   $this->args['secretKey'];
        $this->vendorId   =   $this->args['vendorId'];
        
    }// end function
    
    
    //	@ 반품 / 취소 요청 목록 조회
    //	@ $objCoupangAPI->get_ordersheets($createdAtFrom='2020-08-01', $createdAtTo='2020-08-31', $status='DELIVERING' )
    //	@ https://developers.coupang.com/hc/ko/articles/360033919613
    public function get_returnRequests( $createdAtFrom, $createdAtTo, $cancelType, $status=null, $nextToken=null):array
    {
        $path      		=   "/v2/providers/openapi/apis/api/v4/vendors/{vendorId}/returnRequests";
        $path      		=   str_replace("{vendorId}", $this->vendorId, $path);
        
        $param			.=	"maxPerPage=50";
        $param			=	"&createdAtFrom=".$createdAtFrom;
        $param			.=	"&createdAtTo=".$createdAtTo;
        $param			.=	"&cancelType=".$cancelType;
        
        switch( $cancelType )
        {
            case "CANCEL":
                break;
            default:
                $param			.=	"&status=".$status;
        }///    end switch
        $param			.=	"&nextToken=".$nextToken;
        
        $method         =   "GET";
        $message        =   $this->datetime.$method.$path.$param;
        $algorithm      =   "HmacSHA256";
        $signature      =   hash_hmac('sha256', $message, $this->secretKey);
        $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
        $ReqUrl         =   $this->baseurl.$path.'?'.$param;
        $result			=	$this->curlHttpRequest( $ReqUrl, $method, $authorization);
        
        return json_decode( $result, true );
        
    }// end function
    
    
    
    
	//	@ 발주서 목록 조회( 일단위 페이징 )
	//	@ $objCoupangAPI->get_ordersheets($createdAtFrom='2020-08-01', $createdAtTo='2020-08-31', $status='DELIVERING' )
	//	@ https://developers.coupang.com/hc/ko/articles/360033919573
    public function get_ordersheets_dateRange( $createdAtFrom, $createdAtTo, $status , $nextToken=null):array
    {
      $path      		=   "/v2/providers/openapi/apis/api/v4/vendors/{vendorId}/ordersheets";
      $path      		=   str_replace("{vendorId}", $this->vendorId, $path);

      $param			.=	"maxPerPage=50";
      $param			=	"&createdAtFrom=".$createdAtFrom;
      $param			.=	"&createdAtTo=".$createdAtTo;
      $param			.=	"&status=".$status;
      $param			.=	"&nextToken=".$nextToken;

      $method         =   "GET";
      $message        =   $this->datetime.$method.$path.$param;
      $algorithm      =   "HmacSHA256";
      $signature      =   hash_hmac('sha256', $message, $this->secretKey);
      $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
      $ReqUrl         =   $this->baseurl.$path.'?'.$param;
      $result			=	$this->curlHttpRequest( $ReqUrl, $method, $authorization);

      return json_decode( $result, true );

    }// end function
	
	
	//	@ 상품정보 조회 목록( 페이징 )
	//	@ https://developers.coupang.com/hc/ko/articles/360033645034
    public function get_seller_products_list($nextToken=null)
    {
      $path      		=   "/v2/providers/seller_api/apis/api/v1/marketplace/seller-products";
      $param			=	"";
      $param			.=	"vendorId=".$this->vendorId;
      $param			.=	"&maxPerPage=100";

      if( is_null( $nextToken ) == true )
      {
        //	@ nothing
      }else{
        $param			.=	"&nextToken=".$nextToken;
      }//	end if

      $method         =   "GET";
      $message        =   $this->datetime.$method.$path.$param;
      $algorithm      =   "HmacSHA256";
      $signature      =   hash_hmac('sha256', $message, $this->secretKey);
      $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
      $ReqUrl         =   $this->baseurl.$path.'?'.$param;
      $result			    =   $this->curlHttpRequest( $ReqUrl, $method, $authorization);

      return json_decode( $result, true );

    }// end function
    
	
    //	@ 상품정보 조회 By sellerProductId
    //	@ https://developers.coupang.com/hc/ko/articles/360033645034
    public function get_seller_products_sellerProductId($sellerProductId=null)
    {
        $path      		=   "/v2/providers/seller_api/apis/api/v1/marketplace/seller-products/{sellerProductId}";
        $path      		=   str_replace("{vendorId}", $this->vendorId, $path);
        $path      		=   str_replace("{sellerProductId}", $sellerProductId, $path);

        $method         =   "GET";
        $message        =   $this->datetime.$method.$path;
        $algorithm      =   "HmacSHA256";
        $signature      =   hash_hmac('sha256', $message, $this->secretKey);
        $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
        $ReqUrl         =   $this->baseurl.$path.'?'.$param;
        $result			=	$this->curlHttpRequest( $ReqUrl, $method, $authorization);
        
        return json_decode( $result, true );
        
    }// end function
       
    
    //	@ 상품별 고객문의 조회
    public function get_onlineInquiries($inquiryStartAt=null, $answeredType='ALL', $pageNum=1)
    {
        $path      		=   "/v2/providers/openapi/apis/api/v4/vendors/{vendorId}/onlineInquiries";
        $path      		=   str_replace("{vendorId}", $this->vendorId, $path);
        
        $param			=	"";
        $param			.=	"vendorId=".$this->vendorId;
        $param			.=	"&pageSize=50";
        $param			.=	"&answeredType=".$answeredType;
        
        if( is_null( $inquiryStartAt ) == true )
        {
            $unixtime		=	time();
        }else{
            $unixtime		=	strtotime( $inquiryStartAt );
        }//	end if
        
        $param			.=	"&inquiryStartAt=".date("Y-m-d", $unixtime);
        $param			.=	"&inquiryEndAt=".date("Y-m-d", $unixtime + ( 86400 * 6 ) );
        
        if( is_null( $nextToken ) == true )
        {
            //	@ nothing
        }else{
            $param			.=	"&pageNum=".$pageNum;
        }//	end if
        
        if( is_null( $nextToken ) == true )
        {
            //	@ nothing
        }else{
            $param			.=	"&nextToken=".$nextToken;
        }//	end if
        $method         =   "GET";
        $message        =   $this->datetime.$method.$path.$param;
        $algorithm      =   "HmacSHA256";
        $signature      =   hash_hmac('sha256', $message, $this->secretKey);
        $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
        $ReqUrl         =   $this->baseurl.$path.'?'.$param;
        
        $result			=	$this->curlHttpRequest( $ReqUrl, $method, $authorization);
        
        return json_decode( $result, true );
        
    }// end function	
    
    
    
	
	//	@ 쿠팡 콜센터 문의조회
    public function get_callCenterInquiries($inquiryStartAt=null, $pageNum=1)
    {	
      $path      		=   "/v2/providers/openapi/apis/api/v4/vendors/{vendorId}/callCenterInquiries";
      $path      		=   str_replace("{vendorId}", $this->vendorId, $path);

      $param			=	"";
      $param			.=	"vendorId=".$this->vendorId;
      $param			.=	"&pageSize=50";
      $param			.=	"&partnerCounselingStatus=NONE";

      if( is_null( $inquiryStartAt ) == true )
      {
        $unixtime		=	time();
      }else{
        $unixtime		=	strtotime( $inquiryStartAt );
      }//	end if		

      $param			.=	"&inquiryStartAt=".date("Y-m-d", $unixtime);
      $param			.=	"&inquiryEndAt=".date("Y-m-d", $unixtime + ( 86400 * 6 ) );

      if( is_null( $nextToken ) == true )
      {
        //	@ nothing
      }else{
        $param			.=	"&pageNum=".$pageNum;
      }//	end if


      if( is_null( $nextToken ) == true )
      {
        //	@ nothing
      }else{
        $param			.=	"&nextToken=".$nextToken;
      }//	end if
      $method         =   "GET";
      $message        =   $this->datetime.$method.$path.$param;
      $algorithm      =   "HmacSHA256";
      $signature      =   hash_hmac('sha256', $message, $this->secretKey);
      $authorization  =   "CEA algorithm=HmacSHA256, access-key=".$this->accessKey.", signed-date=".$this->datetime.", signature=".$signature;
      $ReqUrl         =   $this->baseurl.$path.'?'.$param;

      $result			=	$this->curlHttpRequest( $ReqUrl, $method, $authorization);

      return json_decode( $result, true );

    }// end function	
	
	
	private function curlHttpRequest( $ReqUrl, $method, $authorization, $strjson=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ReqUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:  application/json;charset=UTF-8", "Authorization:".$authorization ,"X-EXTENDED-TIMEOUT:60000"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			if( is_null( $strjson ) == true )
			{
				//	@ nothing
			}else{
				curl_setopt($curl, CURLOPT_POSTFIELDS, $strjson);
			}//	end if

		$result		=	curl_exec($ch);
		$httpcode	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $result;

	}//	end function
    
    
    
}// end class



?> 
