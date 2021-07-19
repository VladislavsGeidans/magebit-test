<?php
    namespace App;

    /**
     * Class FormValidator
     * @package App
     */
    class FormValidator
    {
        /**
         * @param string $email
         * @param string $checkbox
         * @return array
         */
        public function validate(string $email, string $checkbox): array
        {
            //1. step: check if email is provided
            if ($email === '') {
                return [
                    'status' => false,
                    'message' => 'Email address is required'
                ];
            }

            //2. step: check if email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'status' => false,
                    'message' => 'Please provide a valid e-mail address'
                ];
            }

            //3. step: check if checkbox is marked
            if ($checkbox === '') {
                return [
                    'status' => false,
                    'message' => 'You must accept the terms and conditions'
                ];
            }

            //4. step: check if domain suffix is not .co
            $emailValueArray = explode('@', $email);
            $domainArray = explode('.', $emailValueArray[1]);
            $domainSuffix = $domainArray[1];
            if ($domainSuffix === 'co') {
                return [
                    'status' => false,
                    'message' => 'We are not accepting subscriptions from Colombia emails'
                ];
            }

            return [
                'status' => true,
                'message' => null
            ];
        }
    }