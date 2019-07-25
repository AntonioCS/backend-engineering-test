<?php


namespace App\Service\DataAnalyzer\Formatters;


use App\Service\DataAnalyzer\Result;


class TextFormatter
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function output(Result $result) : string
    {
        return $this->twig->render('dataAnalyser.html.twig', ['data' => $result]);
    }
}
