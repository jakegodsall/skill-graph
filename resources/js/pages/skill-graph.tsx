import React, { useState, useEffect, useCallback } from 'react';
import { Head } from '@inertiajs/react';
import {
  ReactFlow,
  MiniMap,
  Controls,
  Background,
  useNodesState,
  useEdgesState,
  addEdge,
  Connection,
  Edge,
  Node,
  NodeTypes,
  BackgroundVariant,
} from 'reactflow';
import 'reactflow/dist/style.css';

import AppLayout from '@/layouts/app-layout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Plus, BookOpen, Code, Award, FileText, Target, Zap } from 'lucide-react';

import CourseNode from '@/components/flow-nodes/course-node';
import ProjectNode from '@/components/flow-nodes/project-node';
import BookNode from '@/components/flow-nodes/book-node';
import PracticeNode from '@/components/flow-nodes/practice-node';
import CertificationNode from '@/components/flow-nodes/certification-node';
import OtherNode from '@/components/flow-nodes/other-node';

interface Skill {
  id: number;
  name: string;
  description?: string;
  color: string;
  activities_count: number;
}

interface Activity {
  id: number;
  name: string;
  description?: string;
  type: 'course' | 'project' | 'book' | 'practice' | 'certification' | 'other';
  status: 'not_started' | 'in_progress' | 'completed' | 'paused';
  url?: string;
  position_x?: number;
  position_y?: number;
  skill_id: number;
  skill: Skill;
  depends_on: Activity[];
  required_by: Activity[];
}

const nodeTypes: NodeTypes = {
  course: CourseNode,
  project: ProjectNode,
  book: BookNode,
  practice: PracticeNode,
  certification: CertificationNode,
  other: OtherNode,
};

const getNodeIcon = (type: Activity['type']) => {
  switch (type) {
    case 'course': return <BookOpen className="w-4 h-4" />;
    case 'project': return <Code className="w-4 h-4" />;
    case 'book': return <FileText className="w-4 h-4" />;
    case 'practice': return <Target className="w-4 h-4" />;
    case 'certification': return <Award className="w-4 h-4" />;
    case 'other': return <Zap className="w-4 h-4" />;
  }
};

const getStatusColor = (status: Activity['status']) => {
  switch (status) {
    case 'not_started': return 'bg-gray-100 text-gray-800';
    case 'in_progress': return 'bg-blue-100 text-blue-800';
    case 'completed': return 'bg-green-100 text-green-800';
    case 'paused': return 'bg-yellow-100 text-yellow-800';
  }
};

export default function SkillGraph() {
  const [skills, setSkills] = useState<Skill[]>([]);
  const [activities, setActivities] = useState<Activity[]>([]);
  const [selectedSkill, setSelectedSkill] = useState<string>('all');
  const [nodes, setNodes, onNodesChange] = useNodesState([]);
  const [edges, setEdges, onEdgesChange] = useEdgesState([]);
  const [loading, setLoading] = useState(true);

  // Fetch skills
  const fetchSkills = useCallback(async () => {
    try {
      const response = await fetch('/api/skills');
      const data = await response.json();
      setSkills(data);
    } catch (error) {
      console.error('Error fetching skills:', error);
    }
  }, []);

  // Fetch activities
  const fetchActivities = useCallback(async () => {
    try {
      const url = selectedSkill === 'all' ? '/api/activities' : `/api/activities?skill_id=${selectedSkill}`;
      const response = await fetch(url);
      const data = await response.json();
      setActivities(data);
    } catch (error) {
      console.error('Error fetching activities:', error);
    }
  }, [selectedSkill]);

  // Convert activities to nodes and edges
  const convertToNodesAndEdges = useCallback(() => {
    const newNodes: Node[] = activities.map((activity) => ({
      id: activity.id.toString(),
      type: activity.type,
      position: {
        x: activity.position_x || Math.random() * 400,
        y: activity.position_y || Math.random() * 400,
      },
      data: {
        ...activity,
        label: activity.name,
        icon: getNodeIcon(activity.type),
        statusColor: getStatusColor(activity.status),
      },
    }));

    const newEdges: Edge[] = activities.flatMap((activity) =>
      activity.depends_on.map((dependency) => ({
        id: `${dependency.id}-${activity.id}`,
        source: dependency.id.toString(),
        target: activity.id.toString(),
        type: 'smoothstep',
        animated: activity.status === 'in_progress',
        style: { stroke: activity.skill.color },
      }))
    );

    setNodes(newNodes);
    setEdges(newEdges);
  }, [activities, setNodes, setEdges]);

  // Handle node drag end (save position)
  const onNodeDragStop = useCallback(
    async (event: React.MouseEvent, node: Node) => {
      try {
        await fetch(`/api/activities/${node.id}/position`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          },
          body: JSON.stringify({
            position_x: node.position.x,
            position_y: node.position.y,
          }),
        });
      } catch (error) {
        console.error('Error saving node position:', error);
      }
    },
    []
  );

  // Load data on component mount
  useEffect(() => {
    const loadData = async () => {
      setLoading(true);
      await fetchSkills();
      await fetchActivities();
      setLoading(false);
    };
    loadData();
  }, [fetchSkills, fetchActivities]);

  // Convert activities to nodes and edges when activities change
  useEffect(() => {
    convertToNodesAndEdges();
  }, [convertToNodesAndEdges]);

  const onConnect = useCallback(
    (params: Edge | Connection) => setEdges((eds) => addEdge(params, eds)),
    [setEdges]
  );

  if (loading) {
    return (
      <AppLayout>
        <Head title="Skill Graph" />
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
        </div>
      </AppLayout>
    );
  }

  return (
    <AppLayout>
      <Head title="Skill Graph" />
      
      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Skill Graph</h1>
            <p className="text-gray-600">Visualize your learning journey and skill dependencies</p>
          </div>
          <div className="flex items-center space-x-4">
            <Select value={selectedSkill} onValueChange={setSelectedSkill}>
              <SelectTrigger className="w-48">
                <SelectValue placeholder="Select skill" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Skills</SelectItem>
                {skills.map((skill) => (
                  <SelectItem key={skill.id} value={skill.id.toString()}>
                    <div className="flex items-center space-x-2">
                      <div
                        className="w-3 h-3 rounded-full"
                        style={{ backgroundColor: skill.color }}
                      />
                      <span>{skill.name}</span>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
            <Button>
              <Plus className="w-4 h-4 mr-2" />
              Add Activity
            </Button>
          </div>
        </div>

        {/* Skills Overview */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          {skills.map((skill) => (
            <Card key={skill.id} className="cursor-pointer hover:shadow-md transition-shadow">
              <CardHeader className="pb-3">
                <div className="flex items-center justify-between">
                  <div
                    className="w-4 h-4 rounded-full"
                    style={{ backgroundColor: skill.color }}
                  />
                  <Badge variant="secondary">{skill.activities_count}</Badge>
                </div>
                <CardTitle className="text-lg">{skill.name}</CardTitle>
              </CardHeader>
              {skill.description && (
                <CardContent className="pt-0">
                  <p className="text-sm text-gray-600">{skill.description}</p>
                </CardContent>
              )}
            </Card>
          ))}
        </div>

        {/* React Flow Graph */}
        <Card className="h-96">
          <CardContent className="p-0 h-full">
            <ReactFlow
              nodes={nodes}
              edges={edges}
              onNodesChange={onNodesChange}
              onEdgesChange={onEdgesChange}
              onConnect={onConnect}
              onNodeDragStop={onNodeDragStop}
              nodeTypes={nodeTypes}
              fitView
              attributionPosition="bottom-left"
            >
              <Controls />
              <MiniMap />
              <Background variant={BackgroundVariant.Dots} gap={12} size={1} />
            </ReactFlow>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
} 