<?php
    namespace App\Model;

    class Subscription
    {
        const TABLE_NAME = 'subscriptions';

        /**
         * @var int
         */
        private int $id;

        /**
         * @var string
         */
        private string $email;

        /**
         * @var \DateTime
         */
        private \DateTime  $createdAt;

        /**
         * @param int $id
         */
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        /**
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * @param string $email
         */
        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }

        /**
         * @param \DateTime $createdAt
         */
        public function setCreatedAt(\DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedAt(): \DateTime
        {
            return $this->createdAt;
        }
    }
