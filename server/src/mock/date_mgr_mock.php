<?php
    class DateManagerMock
    {
        private int $date;

        public function __construct(int $date)
        {
            $this->date = $date;
        }

        public function getCurrentDate(): int
        {
            return $this->date;
        }
    }
?>