<?php
/**
 * @package    JVoter
 * @copyright  Copyright (C) 2019 JVoter. All rights reserved.
 * @license    GNU General Public License version 3, or later
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Features list controller class.
 *
 * @since 1.6
 */
class JVoterControllerFeatures extends JControllerAdmin
{

    /**
     * Method to clone existing Plans
     *
     * @return void
     */
    public function duplicate()
    {
        // Check for request forgeries
        Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        // Get id(s)
        $pks = $this->input->post->get('cid', array(), 'array');
        
        try
        {
            if(empty($pks))
            {
                throw new Exception(JText::_('COM_JVOTER_NO_ITEMS_SELECTED'));
            }
            
            ArrayHelper::toInteger($pks);
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(JText::_('COM_JVOTER_ITEMS_SUCCESS_DUPLICATED'));
        } catch(Exception $e)
        {
            $this->app->enqueueMessage($e->getMessage(), 'warning');
        }
        
        $this->setRedirect('index.php?option=com_jvoter&view=features');
    }

    /**
     * Proxy for getModel.
     *
     * @param string $name
     *            Optional. Model name
     * @param string $prefix
     *            Optional. Class prefix
     * @param array $config
     *            Optional. Configuration array for model
     *            
     * @return object The Model
     *        
     * @since 1.6
     */
    public function getModel($name = 'feature', $prefix = 'JVoterModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array(
            'ignore_request' => true
        ));
        
        return $model;
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return void
     *
     * @since 3.0
     */
    public function saveOrderAjax()
    {
        // Get the input
        $pks = $this->input->post->get('cid', array(), 'array');
        $order = $this->input->post->get('order', array(), 'array');
        
        // Sanitize the input
        ArrayHelper::toInteger($pks);
        ArrayHelper::toInteger($order);
        
        // Get the model
        $model = $this->getModel();
        
        // Save the ordering
        $return = $model->saveorder($pks, $order);
        
        if($return)
        {
            echo "1";
        }
        
        // Close the application
        $this->app->close();
    }
}
