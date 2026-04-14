<?php

namespace App\Controller;

use App\Exception\TimeoutException;
use App\Exception\UnauthorizedException;
use App\Service\EmailListService;
use Twig\Environment;

class ListController
{
    private EmailListService $listService;
    private Environment $twig;

    public function __construct(EmailListService $listService, Environment $twig)
    {
        $this->listService = $listService;
        $this->twig = $twig;
    }

    private function renderError(string $message): void
    {
        echo $this->twig->render('error.html.twig', ['message' => $message]);
    }

    public function index(): void
    {
        try {
            $lists = $this->listService->getLists();
            echo $this->twig->render('list.html.twig', ['lists' => $lists]);
        } catch (UnauthorizedException $e) {
            $this->renderError('Érvénytelen / hibás API kulcs.');
        } catch (TimeoutException $e) {
            $this->renderError('Nagy terheltség, próbálja később.');
        }
    }

    public function subscribers(int $id): void
    {
        try {
            $subscribers = $this->listService->getSubscribers($id);

            $sort     = $_GET['sort']     ?? '';
            $isActive = isset($_GET['isActive']) && $_GET['isActive'] === '1';
            $search   = trim($_GET['search'] ?? '');

            $subscribers = $this->sortAndFilter($subscribers, $sort, $isActive, $search);

            echo $this->twig->render('subscribers.html.twig', [
                'subscribers' => $subscribers,
                'sort'        => $sort,
                'isActive'    => $isActive,
                'search'      => $search,
                'listId'      => $id,
            ]);
        } catch (UnauthorizedException $e) {
            $this->renderError('Érvénytelen / hibás API kulcs.');
        } catch (TimeoutException $e) {
            $this->renderError('Nagy terheltség, próbálja később.');
        }
    }

    private function sortAndFilter(array $subscribers, string $sort, bool $isActive, string $search): array
    {
        if ($isActive) {
            $subscribers = array_values(array_filter($subscribers, fn($s) => $s->isActive()));
        }
        if ($search !== '') {
            $subscribers = array_values(array_filter($subscribers, fn($s) =>
                str_contains(strtolower($s->getFullName()), strtolower($search)) ||
                str_contains(strtolower($s->getEmail()), strtolower($search))
            ));
        }

        match ($sort) {
            'name_asc'  => usort($subscribers, fn($a, $b) => strcmp($a->getFullName(), $b->getFullName())),
            'name_desc' => usort($subscribers, fn($a, $b) => strcmp($b->getFullName(), $a->getFullName())),
            'date_asc'  => usort($subscribers, fn($a, $b) => strcmp($a->getSubdate(), $b->getSubdate())),
            'date_desc' => usort($subscribers, fn($a, $b) => strcmp($b->getSubdate(), $a->getSubdate())),
            default     => null,
        };

        return $subscribers;
    }
}
