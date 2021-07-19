<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    require '../vendor/autoload.php';
    session_start();

    use App\Model\Subscription;
    use App\Controller\SubscriptionController;
    use App\FormValidator;

    $action = $_GET['a'] ? : '';

    switch ($action)
    {
        case "sortBy":
            $sortBy = trim($_POST['sortBy']);
            $sortType = trim($_POST['sortType']);

            $_SESSION['sorting'][$sortBy] = $sortType;

            $subscriptionController = new SubscriptionController();
            echo json_encode($subscriptionController->getAll($_SESSION['sorting'], $_SESSION['searchString'], $_SESSION['emailProviderFilter']));

            break;
        case "search":
            $searchString = trim($_POST['searchString']);

            $_SESSION['searchString'] = $searchString;

            $subscriptionController = new SubscriptionController();
            echo json_encode($subscriptionController->getAll($_SESSION['sorting'], $_SESSION['searchString'], $_SESSION['emailProviderFilter']));

            break;
        case "selectEmailProvider":
            $emailProvider = trim($_POST['emailProvider']);

            $_SESSION['emailProviderFilter'] = $emailProvider;
            $subscriptionController = new SubscriptionController();
            echo json_encode($subscriptionController->getAll($_SESSION['sorting'], $_SESSION['searchString'], $_SESSION['emailProviderFilter']));

            break;
        case "subscribe":
            $emailValue = trim($_POST['email']);
            $checkboxValue = ($_POST['checkbox']) ?: '';

            $formValidator = new FormValidator();
            $result = $formValidator->validate($emailValue, $checkboxValue);

            if ($result['status'] == true) {
                $subscription = new Subscription();
                $subscriptionController = new SubscriptionController();

                $subscription->setEmail($emailValue);
                $subscription->setCreatedAt(new DateTime());

                try {
                    if ($subscriptionController->create($subscription)) {
                        echo json_encode($result);
                    }
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            } else {
                echo json_encode($result);
            }

            break;
        case "delete":
            $subscriptionId = (int) $_POST['subscriptionId'];

            $subscriptionController = new SubscriptionController();
            $subscriptionController->remove($subscriptionId);
            echo json_encode($subscriptionController->getAll($_SESSION['sorting'], $_SESSION['searchString'], $_SESSION['emailProviderFilter']));

            break;
        case "getEmailsProviders":

            $subscriptionController = new SubscriptionController();
            echo json_encode($subscriptionController->getEmailsProvider($_SESSION['emailProviderFilter']));

            break;
    }