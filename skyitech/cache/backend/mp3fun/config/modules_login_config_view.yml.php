<?php
// auto-generated by sfViewConfigHandler
// date: 2017/11/12 15:28:56
$context  = $this->getContext();
$response = $context->getResponse();

if ($this->actionName.$this->viewName == 'dev')
{
  $templateName = $response->getParameter($this->moduleName.'_'.$this->actionName.'_template', $this->actionName, 'symfony/action/view');
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());
}
else
{
  $templateName = $response->getParameter($this->moduleName.'_'.$this->actionName.'_template', $this->actionName, 'symfony/action/view');
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());
}

if ($templateName.$this->viewName == 'dev')
{
  if (!$context->getRequest()->isXmlHttpRequest())
  {
    $this->setDecoratorTemplate('layout'.$this->getExtension());
  }
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'dev-1m.in - Admin - funpro.in &raquo; Powered By mp3fun.tk', false, false);
  $response->addMeta('robots', 'no-index, no-follow', false, false);
  $response->addMeta('language', 'en', false, false);

  $response->addStylesheet('main', '', array ());
  $response->addJavascript('/js/jquery.min.js');
  $response->addJavascript('/js/ckeditor/ckeditor.js');
}
else
{
  if (!$context->getRequest()->isXmlHttpRequest())
  {
    $this->setDecoratorTemplate('layout'.$this->getExtension());
  }
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'mp3fun.tk - Admin  &raquo; Powered By mp3fun.tk', false, false);
  $response->addMeta('robots', 'no-index, no-follow', false, false);
  $response->addMeta('language', 'en', false, false);

  $response->addStylesheet('main', '', array ());
  $response->addJavascript('/js/jquery.min.js');
  $response->addJavascript('/js/ckeditor/ckeditor.js');
}

