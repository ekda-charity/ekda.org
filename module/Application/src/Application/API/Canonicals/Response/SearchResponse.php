<?php

namespace Application\API\Canonicals\Response {
    class SearchResponse extends Response {
        public $total = 0;
        public $totalPages = 0;
        public $page = 0;
        public $pageSize = 0;
        public $items;
    }

}
