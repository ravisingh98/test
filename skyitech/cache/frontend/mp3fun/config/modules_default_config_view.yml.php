<?php
// auto-generated by sfViewConfigHandler
// date: 2017/11/12 15:17:07
$context  = $this->getContext();
$response = $context->getResponse();

if ($this->actionName.$this->viewName == 'error404Success')
{
  $templateName = $response->getParameter($this->moduleName.'_'.$this->actionName.'_template', $this->actionName, 'symfony/action/view');
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());
}
else
{
  $templateName = $response->getParameter($this->moduleName.'_'.$this->actionName.'_template', $this->actionName, 'symfony/action/view');
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());
}

if ($templateName.$this->viewName == 'error404Success')
{
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'mp3fun.tk &raquo; Development By mp3fun.tk', false, false);
  $response->addMeta('robots', 'index, follow', false, false);
  $response->addMeta('language', 'en', false, false);


}
else
{
  if (!$context->getRequest()->isXmlHttpRequest())
  {
    $this->setDecoratorTemplate('layout'.$this->getExtension());
  }
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'mp3fun.tk &raquo; Development By mp3fun.tk', false, false);
  $response->addMeta('robots', 'index, follow', false, false);
  $response->addMeta('language', 'en', false, false);


}

