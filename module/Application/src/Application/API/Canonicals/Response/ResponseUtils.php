<?php

namespace Application\API\Canonicals\Response {

    class ResponseUtils {
        public static function createExceptionResponse(\Exception $ex) {
            $response = new Response();
            $response->success = false;
            array_push($response->errors, $ex->getMessage());
            return $response;
        }
        
        public static function createResponse(array $errors = array(), array $warnings = array()) {
            $response = new Response();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            return $response;
        }
        
        public static function createWriteResponse($writtenKey, array $errors = array(), array $warnings = array()) {
            $response = new WriteResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->writtenKey = $response->success ? $writtenKey : null;
            return $response;
        }
        
        public static function createFetchResponse(array $items, array $errors = array(), array $warnings = array()) {
            $response = new FetchResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->items = $response->success ? $items : array();
            return $response;
        }
        
        public static function createSingleFetchResponse($item, array $errors = array(), array $warnings = array()) {
            $response = new SingleFetchResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->item = $response->success ? $item : null;
            return $response;
        }
        
        public static function createMultiWriteResponse(array $keyMap, array $errors = array(), array $warnings = array()) {
            $response = new MultiWriteResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->keyMap = $response->success ? $keyMap : null;
            return $response;
        }
        
        public static function createSearchResponse($total, $items, $page, $pageSize, array $errors = array(), array $warnings = array()) {
            $response = new SearchResponse();
            $response->errors = $errors;
            $response->warnings = $warnings;
            $response->success = count($errors) == 0;
            $response->items = $response->success ? $items : array();
            $response->total = $total;
            
            $response->pageSize   = $pageSize;
            $response->totalPages = ceil($total / $pageSize);
            $response->page       = $page;
            
            return $response;
        }
        
    }

}
