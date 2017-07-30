<?php
namespace hbynlsl\View;

/**
 * View interface
 * 
 * @author Elger van Boxtel
 */
interface IView
{

    /**
     * Renders the view
     *
     * @return String the view's content
     * 
     * @throws ViewException when something went wrong during render phase
     */
    public function render();
}