<?php namespace CSI\BbCodeMap\BbCode\Formatter;

/**
 * Class Base
 * @package CSI\BbCodeMap\BbCode\Formatter
 */
class Base
{
  /**
   * @param array $tag
   * @param array $rendererStates
   * @param \XenForo_BbCode_Formatter_Base $formatter
   * @return mixed
   */
  public static function getBbCodeMap(array $tag, array $rendererStates, \XenForo_BbCode_Formatter_Base $formatter)
  {
    $xenOptions = \XenForo_Application::get('options');
    $xenVisitor = \XenForo_Visitor::getInstance();
    $xenVisitor_id = $xenVisitor->getUserId();
    $tagOption = array_map('trim', explode('|', $tag['option']));

    if (count($tagOption) > 1) {
      $optDefault = $tagOption[0];
    } else {
      $optDefault = $tag['option'];
    }

    $tagContent = $formatter->renderSubTree($tag['children'], $rendererStates);

    if (!preg_match('#^(.*?)$#', $tagContent)) {
      return $formatter->renderInvalidTag($tag, $rendererStates);
    }

    $token = $xenVisitor_id . '-' . $tagContent . '-' . uniqid(rand(), 1);
    $map_id = hash('sha1', $token);
    $view = $formatter->getView();

    if ($view) {
      $template = $view->createTemplateObject('csiXF_bbCode_3A77763A_tag_map',
        array(
          'content' => $tagContent,
          'map_id' => $map_id,
        ));

      $tagContent = $template->render();
      return trim($tagContent);
    }

    return $tagContent;
  }
}
